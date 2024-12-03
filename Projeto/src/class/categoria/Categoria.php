<?php

require_once __DIR__.'/../Conexao.php';
require_once __DIR__.'/../validacao/ValidacaoException.php';

class Categoria 
{
    protected Conexao $conexao;

    /**
     * Construtor da classe. Cria uma nova conex o com o banco de dados.
     */
    public function __construct() {
        $this->conexao = new Conexao();
    }
    
    /**
     * Cadastra ou atualiza uma categoria de produto no banco de dados.
     *
     * Se um ID de categoria for fornecido, a categoria existente será atualizada.
     * Caso contrário, uma nova categoria será inserida.
     *
     * @param int|null $id O ID da categoria (opcional para inserção).
     * @param string $nome O nome da categoria.
     * @param string|null $pagina A página associada à categoria (opcional).
     * @return array Os dados da categoria cadastrada ou atualizada.
     */
    public function cadastrar(int $id=null, string $nome, string $pagina=null): array
    {
        $query = 'INSERT INTO categoria_produto (nome, pagina) VALUES (:nome, :pagina)';
        $params = [
            'nome' => $nome,
            'pagina' => $pagina
        ];

        if (!empty($id)) {
            $query = 'UPDATE categoria_produto SET nome = :nome, pagina = :pagina WHERE IdCategoria = :id';
            $params['id'] = $id;
        }

        $stmt = $this->conexao->prepare($query);
        $stmt->execute($params);
        $id_categoria = max($this->conexao->lastInsertId(), $id);

        $query = 'SELECT cat.* FROM categoria_produto cat WHERE IdCategoria = :id';
        $stmt = $this->conexao->prepare($query);
        $stmt->execute(['id' => $id_categoria]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    /**
     * Marca uma categoria de produto como excluída no banco de dados.
     *
     * Atualiza o campo DataExclusao da categoria com a data e hora atuais.
     *
     * @param int $id O ID da categoria a ser excluída.
     * @return void
     */
    public function excluir(int $id): void
    {
        $query = <<<SQL
        
        UPDATE categoria_produto
        SET DataExclusao = CURRENT_TIMESTAMP()
        WHERE IdCategoria = :id
SQL;
        $stmt = $this->conexao->prepare($query);
        $stmt->execute([
            'id' => $id
        ]);
    }
    
    /**
     * Retorna todas as categorias de produtos ativas no banco de dados.
     *
     * As categorias são retornadas em ordem alfabética.
     *
     * @return array Um array com os dados das categorias.
     */
    public function listarTudo(): array
    {
        $query = <<<SQL

        SELECT cat.*
        FROM categoria_produto cat
        WHERE cat.dataexclusao IS NULL
        ORDER BY cat.nome;
SQL;

        $stmt = $this->conexao->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(Conexao::FETCH_ASSOC);
    }

    /**
     * Retorna as categorias de produtos ativas associadas a uma determinada página.
     *
     * As categorias são filtradas com base na página fornecida e retornadas em ordem alfabética.
     *
     * @param string $pagina O identificador da página associada às categorias.
     * @return array Um array com os dados das categorias associadas à página.
     */
    public function listarPorPagina(string $pagina): array
    {
        $query = <<<SQL

        SELECT cat.*
        FROM categoria_produto cat
        WHERE cat.dataexclusao IS NULL
            AND cat.pagina = :pagina
        ORDER BY cat.nome;
SQL;

        $stmt = $this->conexao->prepare($query);
        $stmt->execute(['pagina' => $pagina]);

        return $stmt->fetchAll(Conexao::FETCH_ASSOC);
    }
}