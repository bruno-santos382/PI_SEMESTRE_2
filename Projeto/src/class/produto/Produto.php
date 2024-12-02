<?php

require_once __DIR__.'/../Conexao.php';

class Produto 
{
    protected Conexao $conexao;

    public function __construct() {
        $this->conexao = new Conexao();
    }

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