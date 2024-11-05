<?php

class Produto {
    protected Conexao $conexao;

    public function __construct() {
        $this->conexao = new Conexao();
    }

    public function cadastrar(): array 
    {
        $dados = $this->obterDadosProduto();

        $query = <<<SQL
            INSERT INTO produtos (nome, preco, marca, estoque) 
            VALUES (:nome, :preco, :marca, :estoque);
SQL;
        $stmt = $this->conexao->prepare($query);
        $stmt->execute([
            'nome' => $dados['nome'],
            'preco' => $dados['preco'],
            'marca' => $dados['marca'],
            'estoque' => $dados['estoque'],
        ]);
        $stmt->closeCursor();

        return ['sucesso' => true];
    }

    public function atualizar(): array 
    {
        $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
        if (empty($id)) {
            throw new \Exception('O código do produto é obrigatório e deve ser um número inteiro positivo.');
        }

        $dados = $this->obterDadosProduto();

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
            'id' => $dados['id'],
            'nome' => $dados['nome'],
            'preco' => $dados['preco'],
            'marca' => $dados['marca'],
            'estoque' => $dados['estoque'],
        ]);
        $stmt->closeCursor();

        return ['sucesso' => true];
    }

    public function excluir() {
        $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
        
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
        $stmt->closeCursor();

        return ['sucesso' => true];
    }

    public function buscar() {
        $termo = filter_input(INPUT_POST, 'termo', FILTER_DEFAULT);

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

        $dados = $stmt->fetchAll(Conexao::FETCH_ASSOC);
        $stmt->closeCursor();

        return ['sucesso' => true, 'dados' => $dados];
    }

    private function obterDadosProduto(): array {
        // Define os filtros para os dados do produto
        $filtros = [
            'nome' => FILTER_DEFAULT,
            'preco' => FILTER_VALIDATE_FLOAT,
            'marca' => FILTER_DEFAULT,
            'estoque' => FILTER_VALIDATE_INT,
        ];

        // Usa filter_input_array para obter e filtrar os dados de entrada
        $dados = filter_input_array(INPUT_POST, $filtros);

        if (empty($dados['nome'])) {
            throw new \Exception('O nome do produto é obrigatório e não pode estar vazio.');
        }
        if (!is_numeric($dados['preco'])) {
            throw new \Exception('O preço do produto é obrigatório e deve ser um valor numérico válido.');
        }
        if (empty($dados['marca'])) {
            throw new \Exception('A marca do produto é obrigatória e não pode estar vazia.');
        }
        if (!is_numeric($dados['estoque'])) {
            throw new \Exception('A quantidade em estoque do produto é obrigatória e deve ser um número inteiro válido.');
        }

        return $dados;
    }
}