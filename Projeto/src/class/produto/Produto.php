<?php

require_once __DIR__.'/../Conexao.php';

class Produto 
{
    protected Conexao $conexao;


    /**
     * Constroi um novo objeto Produto.
     * 
     * Instancia uma nova conexão com o banco de dados.
     */
    public function __construct() {
        $this->conexao = new Conexao();
    }

    /**
     * Cadastra um novo produto no banco de dados.
     *
     * @param string $nome O nome do produto.
     * @param float $preco O preço do produto.
     * @param string $marca A marca do produto.
     * @param int $categoria O ID da categoria do produto.
     * @param int $estoque A quantidade de estoque disponível.
     * @param int|null $id_imagem O ID da imagem associada ao produto (opcional).
     * 
     * @throws \PDOException Se ocorrer um erro ao executar a consulta.
     */
    public function cadastrar(string $nome, float $preco, string $marca, int $categoria, int $estoque, int $id_imagem=null): void 
    {        
        $query = <<<SQL
            INSERT INTO produtos (nome, preco, marca, idcategoria, estoque, idimagem) 
            VALUES (:nome, :preco, :marca, :categoria, :estoque, :idimagem);
SQL;
        $stmt = $this->conexao->prepare($query);
        $stmt->execute([
            'nome' => $nome,
            'preco' => $preco,
            'marca' => $marca,
            'categoria' => $categoria,
            'estoque' => $estoque,
            'idimagem' => $id_imagem
        ]);
    }

    /**
     * Atualiza um produto no banco de dados.
     * 
     * Se o ID do produto for nulo, lança uma exceção.
     * Caso contrário, atualiza o produto com os dados fornecidos.
     * 
     * @param int $id O ID do produto.
     * @param string $nome O nome do produto.
     * @param float $preco O preço do produto.
     * @param string $marca A marca do produto.
     * @param int $categoria O ID da categoria do produto.
     * @param int $estoque A quantidade de estoque disponível.
     * @param int|null $id_imagem O ID da imagem associada ao produto (opcional).
     * 
     * @throws \Exception Se o ID do produto for nulo.
     */
    public function atualizar(int $id, string $nome, float $preco, string $marca, int $categoria, int $estoque, int $id_imagem=null): void 
    {
        if (empty($id)) {
            throw new \Exception('O código do produto é obrigatório.');
        }

        $query = <<<SQL
            UPDATE produtos 
                SET nome = :nome,
                    preco = :preco,
                    marca = :marca,
                    idcategoria = :categoria,
                    estoque = :estoque,
                    idimagem = :idimagem
            WHERE idproduto = :id;
SQL;
        $stmt = $this->conexao->prepare($query);
        $stmt->execute([
            'id' => $id,
            'nome' => $nome,
            'preco' => $preco,
            'marca' => $marca,
            'categoria' => $categoria,
            'estoque' => $estoque,
            'idimagem' => $id_imagem
        ]);
    }

    /**
     * Marca um produto como excluído no banco de dados.
     * 
     * Atualiza o campo DataExclusao do produto com a data e hora atuais.
     * 
     * @param int $id O ID do produto a ser excluído.
     * 
     * @throws \Exception Se o ID do produto for nulo.
     */
    public function excluir(int $id) {        
        if (empty($id)) {
            throw new \Exception('O código do produto é obrigatório.');
        }

        $query = <<<SQL
        UPDATE produtos
        SET DataExclusao = CURRENT_TIMESTAMP()
        WHERE IdProduto = :id
SQL;
        $stmt = $this->conexao->prepare($query);
        $stmt->execute([
            'id' => $id
        ]);
    }

    /**
     * Busca um produto pelo seu identificador único.
     * 
     * @param int $id Identificador único do produto.
     * 
     * @return array|false Um array com os dados do produto, ou false se não encontrar.
     */
    public function buscaPorId(int $id): array|false
    {
        $query = <<<SQL

        SELECT *
        FROM VW_PRODUTOS_ATIVOS
        WHERE dataexclusao IS NULL AND idproduto = :id
SQL;

        $stmt = $this->conexao->prepare($query);
        $stmt->execute(['id' => $id]);

        return $stmt->fetch(Conexao::FETCH_ASSOC);
    }


    /**
     * Retorna todos os produtos existentes.
     * 
     * @return array Retorna um array com os dados dos produtos.
     */
    public function listarTudo(): array
    {
        $query = <<<SQL

        SELECT *
        FROM VW_PRODUTOS_ATIVOS
        WHERE dataexclusao IS NULL
        ORDER BY idproduto ASC, nome ASC;
SQL;

        $stmt = $this->conexao->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(Conexao::FETCH_ASSOC);
    }

    /**
     * Retorna todos os produtos existentes de uma determinada categoria.
     * 
     * Apenas produtos com estoque maior que zero são retornados.
     * 
     * @param string $categoria O nome da categoria dos produtos.
     * 
     * @return array Retorna um array com os dados dos produtos.
     */
    public function listarPorCategoria(string $categoria): array
    {
        $query = <<<SQL

        SELECT *
        FROM VW_PRODUTOS_ATIVOS
        WHERE dataexclusao IS NULL
            AND categoria = :categoria
            AND estoque > 0
        ORDER BY idproduto ASC, nome ASC;
SQL;

        $stmt = $this->conexao->prepare($query);
        $stmt->execute(['categoria' => $categoria]);

        return $stmt->fetchAll(Conexao::FETCH_ASSOC);
    }
}