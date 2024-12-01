<?php

require_once __DIR__.'/../Conexao.php';

class Pedido 
{
    protected Conexao $conexao;

    public function __construct() {
        $this->conexao = new Conexao();
    }

    public function cadastrar(
        int $cliente, 
        string $data_pedido, 
        float $valor_total,
        string $data_retirada = null,
        int $id = null
    ): array {
        $query = 'INSERT INTO pedidos (IdCliente, DataPedido, DataRetirada, ValorTotal) 
                  VALUES (:cliente, :data_pedido, :data_retirada, :valor_total)';
        $params = [
            'cliente' => $cliente,
            'data_pedido' => $data_pedido,
            'data_retirada' => $data_retirada,
            'valor_total' => $valor_total
        ];
    
        if (!empty($id)) {
            $query = 'UPDATE pedidos 
                      SET IdCliente = :cliente, 
                          DataPedido = :data_pedido, 
                          DataRetirada = :data_retirada, 
                          ValorTotal = :valor_total 
                      WHERE IdPedido = :id';
            $params['id'] = $id;
        }
    
        $stmt = $this->conexao->prepare($query);
        $stmt->execute($params);
        $id_pedido = max($this->conexao->lastInsertId(), $id);
    
        $query = 'SELECT * FROM VW_PEDIDOS_CLIENTE WHERE IdPedido = :id';
        $stmt = $this->conexao->prepare($query);
        $stmt->execute(['id' => $id_pedido]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }
    
    public function cancelar(int $id): array {
        $query = <<<SQL

        UPDATE pedidos
        SET Status = 'Cancelado'
        WHERE IdPedido = :id
SQL;
        $stmt = $this->conexao->prepare($query);
        $stmt->execute(['id' => $id]);

        return $this->buscaPorId($id);
    }

    public function finalizar(int $id): array {
        $query = <<<SQL

        UPDATE pedidos
        SET Status = 'Finalizado'
        WHERE IdPedido = :id
SQL;
        $stmt = $this->conexao->prepare($query);
        $stmt->execute(['id' => $id]);

        return $this->buscaPorId($id);
    }

    public function buscaPorId(int $id): array {
        $query = <<<SQL

        SELECT * FROM VW_PEDIDOS_CLIENTE
        WHERE idpedido = :idpedido
SQL;

        $stmt = $this->conexao->prepare($query);
        $stmt->execute(['idpedido' => $id]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function listarPedidosEmAndamento(): array {
        $query = <<<SQL

        SELECT * FROM VW_PEDIDOS_CLIENTE
        WHERE Status = 'Em Andamento'
        ORDER BY DataPedido, DataRetirada
SQL;

        $stmt = $this->conexao->query($query);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
    
    public function listarPedidosFinalizados(): array {
        $query = <<<SQL

        SELECT * FROM VW_PEDIDOS_CLIENTE
        WHERE Status = 'Finalizado'
        ORDER BY DataPedido, DataRetirada
SQL;

        $stmt = $this->conexao->query($query);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function listarPedidosCancelados(): array {
        $query = <<<SQL

        SELECT * FROM VW_PEDIDOS_CLIENTE
        WHERE Status = 'Cancelado'
        ORDER BY DataPedido, DataRetirada
SQL;

        $stmt = $this->conexao->query($query);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function listaItensPedido(int $id): array {
        $query = <<<SQL

        SELECT p.*, pp.Quantidade, pp.PrecoUnitario, (pp.Quantidade * pp.PrecoUnitario) AS ValorTotal
        FROM pedido_produtos pp
        JOIN VW_PRODUTOS p ON p.idproduto = pp.idproduto
        WHERE pp.idpedido = :idpedido
SQL;

        $stmt = $this->conexao->prepare($query);
        $stmt->execute(['idpedido' => $id]);
        $dados = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        return [
            'itens' => $dados,
            'total' => array_sum(array_column($dados, 'ValorTotal'))
        ];
    }
}
