<?php

require_once __DIR__.'/../Conexao.php';
require_once __DIR__.'/../validacao/ValidacaoException.php';

class Categoria 
{
    protected Conexao $conexao;

    public function __construct() {
        $this->conexao = new Conexao();
    }
    
    public function cadastrar(int $id=null, string $nome, string $pagina=null): void
    {
        $query = 'INSERT INTO categoria_produto (nome, pagina) VALUES (:nome, :pagina)';
        if (!empty($id)) {
            $query = 'UPDATE categoria_produto SET nome = :nome, pagina = :pagina WHERE id = :id';
        }

        $stmt = $this->conexao->prepare($query);
        $stmt->execute([
            'id' => $id,
            'nome' => $nome,
            'pagina' => $pagina
        ]);
    }

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
}