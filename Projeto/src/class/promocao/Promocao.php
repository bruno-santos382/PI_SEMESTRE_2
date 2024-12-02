<?php

require_once __DIR__.'/../Conexao.php';

class Promocao 
{
    protected Conexao $conexao;

    public function __construct() {
        $this->conexao = new Conexao();
    }

    public function cadastrar(
        int $id = null, 
        int $produto, 
        string $data_inicio, 
        string $data_fim, 
        float $desconto
    ): array {
        $query = 'INSERT INTO promocoes (IdProduto, DataInicio, DataFim, Desconto) 
                  VALUES (:produto, :data_inicio, :data_fim, :desconto)';
        $params = [
            'produto' => $produto,
            'data_inicio' => $data_inicio,
            'data_fim' => $data_fim,
            'desconto' => $desconto
        ];
    
        if (!empty($id)) {
            $query = 'UPDATE promocoes 
                      SET IdProduto = :produto, 
                          DataInicio = :data_inicio, 
                          DataFim = :data_fim, 
                          Desconto = :desconto 
                      WHERE IdPromocao = :id';
            $params['id'] = $id;
        }
    
        $stmt = $this->conexao->prepare($query);
        $stmt->execute($params);
        $id_promocao = max($this->conexao->lastInsertId(), $id);
    
        $query = 'SELECT * FROM VW_PROMOCOES_PRODUTO_ATIVAS WHERE IdPromocao = :id';
        $stmt = $this->conexao->prepare($query);
        $stmt->execute(['id' => $id_promocao]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }
    
    public function excluir(int $id): void {
        $query = <<<SQL
        UPDATE promocoes
        SET DataExclusao = CURRENT_TIMESTAMP()
        WHERE IdPromocao = :id
    SQL;
        $stmt = $this->conexao->prepare($query);
        $stmt->execute(['id' => $id]);
    }

    public function listarPromocoesSemana(): array {
        $query = <<<SQL

        SELECT * FROM VW_PROMOCOES_PRODUTO_ATIVAS
        WHERE Status = 'Ativo' AND Estoque > 0 AND CURDATE() BETWEEN DataInicio AND DataFim 
        ORDER BY DataInicio, DataFim
SQL;

        $stmt = $this->conexao->query($query);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
    

    public function listarPromocoesAtivas(): array {
        $query = <<<SQL

        SELECT * FROM VW_PROMOCOES_PRODUTO_ATIVAS
        WHERE Status = 'Ativo'
        ORDER BY DataInicio, DataFim
SQL;

        $stmt = $this->conexao->query($query);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function listarPromocoesExpiradas(): array {
        $query = <<<SQL

        SELECT * FROM VW_PROMOCOES_PRODUTO_ATIVAS
        WHERE Status = 'Expirado'
        ORDER BY DataInicio, DataFim
SQL;

        $stmt = $this->conexao->query($query);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}
