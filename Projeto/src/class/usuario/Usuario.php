<?php

require_once __DIR__.'/../Conexao.php';

class Usuario 
{
    protected Conexao $conexao;

    public function __construct() {
        $this->conexao = new Conexao();
    }

    public function buscar(): array
    {
        $query = <<<SQL

        SELECT * FROM usuarios ORDER BY IdUsuario
SQL;
        $stmt = $this->conexao->query($query);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}