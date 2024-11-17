<?php

require_once __DIR__.'/../Conexao.php';
require_once __DIR__.'/../validacao/ValidacaoException.php';

class Categoria 
{
    protected Conexao $conexao;

    public function __construct() {
        $this->conexao = new Conexao();
    }
    
    public function excluir(int $id) {
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

    public function lista(): array
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