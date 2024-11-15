<?php

require_once __DIR__.'/../Conexao.php';

class Produto 
{
    protected Conexao $conexao;

    public function __construct() {
        $this->conexao = new Conexao();
    }

    public function cadastrar(string $nome, float $preco, string $marca, int $estoque, int $id_imagem=null): void 
    {        
        $query = <<<SQL
            INSERT INTO produtos (nome, preco, marca, estoque, idimagem) 
            VALUES (:nome, :preco, :marca, :estoque, :idimagem);
SQL;
        $stmt = $this->conexao->prepare($query);
        $stmt->execute([
            'nome' => $nome,
            'preco' => $preco,
            'marca' => $marca,
            'estoque' => $estoque,
            'idimagem' => $id_imagem
        ]);
    }

    public function atualizar(int $id, string $nome, float $preco, string $marca, int $estoque, int $id_imagem=null): void 
    {
        if (empty($id)) {
            throw new \Exception('O código do produto é obrigatório.');
        }

        $query = <<<SQL
            UPDATE produtos 
                SET nome = :nome,
                    preco = :preco,
                    marca = :marca,
                    estoque = :estoque,
                    idimagem = :idimagem
            WHERE id = :id;
SQL;
        $stmt = $this->conexao->prepare($query);
        $stmt->execute([
            'id' => $id,
            'nome' => $nome,
            'preco' => $preco,
            'marca' => $marca,
            'estoque' => $estoque,
            'idimagem' => $id_imagem
        ]);
    }

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

    public function buscar(string $termo): array
    {
        $query = <<<SQL
            SELECT p.* 
            FROM produtos p
            WHERE p.nome LIKE CONCAT(:nome, '%')
            AND DataExclusao IS NULL
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

        SELECT 
            p.*, 
            img.caminho AS imagem
        FROM produtos p
        LEFT JOIN imagens img ON img.idimagem = p.idimagem
        WHERE p.dataexclusao IS NULL
        ORDER BY p.idproduto ASC, p.nome ASC;
SQL;

        $stmt = $this->conexao->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(Conexao::FETCH_ASSOC);
    }
}