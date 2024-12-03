<?php

require_once __DIR__.'/../Conexao.php';

class FrangoAssado
{
    protected Conexao $conexao;

    public function __construct() 
    {
        $this->conexao = new Conexao();
    }

    /**
     * Cria um novo pedido de Frango Assado.
     *
     * @param string $nome Nome completo do cliente
     * @param string $telefone Telefone do cliente
     * @param int $quantidade Quantidade de frangos
     * @param string|null $observacoes Observações adicionais (opcional)
     * @param int|null $status Status do pedido (default: 1 - Pendente)
     * 
     * @return array Retorna os dados do pedido inserido/atualizado
     */
    public function novoPedido(
        string $nome, 
        string $telefone, 
        int $quantidade, 
        string $observacoes = null,
        int $id = null
    ): array {
        // Preço do frango assado com desconto
        $preco_unitario = 34.99;

      // Inserir ou atualizar o pedido no banco de dados
        $query = <<<SQL
        INSERT INTO frango_assado_pedidos (Nome, Telefone, Quantidade, Observacoes, Total) 
        VALUES (:nome, :telefone, :quantidade, :observacoes, :total)
SQL;
        $params = [
            'nome' => $nome,
            'telefone' => $telefone,
            'quantidade' => $quantidade,
            'observacoes' => $observacoes,
            'total' => $quantidade * $preco_unitario
        ];

        if (!empty($id)) {
            $query = <<<SQL
                UPDATE frango_assado_pedidos 
                SET Nome = :nome, Telefone = :telefone, Quantidade = :quantidade, Observacoes = :observacoes, Total = :total 
                WHERE IdPedido = :id
SQL;
            $params['id'] = $id;
        }

        $stmt = $this->conexao->prepare($query);
        $stmt->execute($params);
        $id_pedido = max($this->conexao->lastInsertId(), $id); 

        return $this->buscaPorId($id_pedido);
    }

    public function cancelarPedido(int $id): array {
        // Cancela o pedido
        $query = <<<SQL

        UPDATE frango_assado_pedidos
        SET Status = 'Cancelado'
        WHERE IdPedido = :id
SQL;
        $stmt = $this->conexao->prepare($query);
        $stmt->execute(['id' => $id]);

        return $this->buscaPorId($id);
    }

    public function finalizarPedido(int $id): array {
        $query = <<<SQL

        UPDATE frango_assado_pedidos
        SET Status = 'Finalizado'
        WHERE IdPedido = :id
SQL;
        $stmt = $this->conexao->prepare($query);
        $stmt->execute(['id' => $id]);

        return $this->buscaPorId($id);
    }

    public function buscaPorId(int $id): array {
        $query = "SELECT * FROM frango_assado_pedidos WHERE IdPedido = :id";
        $stmt = $this->conexao->prepare($query);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }
   /**
     * Consulta os pedidos pendentes de frango assado.
     * 
     * @return array Retorna os dados dos pedidos pendentes.
     */
    public function listarPedidosPendentes(): array {
        $query = <<<SQL
        
        SELECT *
        FROM frango_assado_pedidos WHERE Status = 'Pendente' 
        ORDER BY DataPedido ASC;
SQL;
        
        // Prepara e executa a consulta no banco de dados
        $stmt = $this->conexao->prepare($query);
        $stmt->execute();

        // Retorna os resultados da consulta
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Consulta os pedidos finalizados de frango assado.
     * 
     * @return array Retorna os dados dos pedidos finalizados.
     */
    public function listarPedidosFinalizados(): array {
        $query = <<<SQL
        
        SELECT *
        FROM frango_assado_pedidos WHERE Status = 'Finalizado' 
        ORDER BY DataPedido ASC;
SQL;
        
        // Prepara e executa a consulta no banco de dados
        $stmt = $this->conexao->prepare($query);
        $stmt->execute();

        // Retorna os resultados da consulta
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }


    /**
     * Consulta os pedidos cancelados de frango assado.
     * 
     * @return array Retorna os dados dos pedidos cancelados.
     */
    public function listarPedidosCancelados(): array {
        $query = <<<SQL
        
        SELECT *
        FROM frango_assado_pedidos WHERE Status = 'Cancelado' 
        ORDER BY DataPedido ASC;
SQL;
        // Prepara e executa a consulta no banco de dados
        $stmt = $this->conexao->prepare($query);
        $stmt->execute();

        // Retorna os resultados da consulta
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }


}
