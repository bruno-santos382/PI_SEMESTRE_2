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
        $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
        if (empty($id)) {
            throw new \Exception('O código do produto é obrigatório e deve ser um número inteiro positivo.');
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
            throw new \Exception('O código do produto é obrigatório e deve ser um número inteiro positivo.');
        }

        $query = <<<SQL
            UPDATE produtos
                SET inativo = 1
                WHERE id = :id
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
                OR p.marca LIKE CONCAT(:marca, '%')
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
        ORDER BY p.nome ASC
SQL;

        $stmt = $this->conexao->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(Conexao::FETCH_ASSOC);
    }
}