<?php

require_once __DIR__.'/../Conexao.php';

class Produto 
{
    protected Conexao $conexao;

    public function __construct() {
        $this->conexao = new Conexao();
    }

    public function cadastrar(string $nome, float $preco, string $marca, int $estoque): void 
    {
        $query = <<<SQL
            INSERT INTO produtos (nome, preco, marca, estoque) 
            VALUES (:nome, :preco, :marca, :estoque);
SQL;
        $stmt = $this->conexao->prepare($query);
        $stmt->execute([
            'nome' => $nome,
            'preco' => $preco,
            'marca' => $marca,
            'estoque' => $estoque,
        ]);
    }

    public function atualizar(int $id, string $nome, float $preco, string $marca, int $estoque): void 
    {
        if (empty($id)) {
            throw new \Exception('O código do produto é obrigatório.');
        }

        $query = <<<SQL
            UPDATE produtos 
                SET nome = :nome,
                    preco = :preco,
                    marca = :marca,
                    estoque = :estoque
            WHERE id = :id;
SQL;
        $stmt = $this->conexao->prepare($query);
        $stmt->execute([
            'id' => $id,
            'nome' => $nome,
            'preco' => $preco,
            'marca' => $marca,
            'estoque' => $estoque,
        ]);
    }

    public function excluir(int $id) {        
        if (empty($id)) {
            throw new \Exception('O código do produto é obrigatório.');
        }

        $query = <<<SQL
        UPDATE produtos
        SET Inativo = 1
        WHERE IdProduto = :id
SQL;
        $stmt = $this->conexao->prepare($query);
        $stmt->execute([
            'id' => $id
        ]);
    }

    public function buscar(string $termo): array
    {
        $query = <<<SQL
            SELECT p.* 
            FROM produtos p
            WHERE p.nome LIKE CONCAT(:nome, '%')
            AND p.inativo = 0
SQL;

        $stmt = $this->conexao->prepare($query);
        $stmt->execute([
            'nome' => $termo,
            'marca' => $termo
        ]);

        return $stmt->fetchAll(Conexao::FETCH_ASSOC);
    }

    public function listar(): array
    {
        $query = <<<SQL
        SELECT p.* 
        FROM produtos p
        WHERE p.inativo = 0
        ORDER BY p.idproduto, p.nome ASC
SQL;

        $stmt = $this->conexao->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(Conexao::FETCH_ASSOC);
    }
}