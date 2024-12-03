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
        string $observacoes = null
    ): array {
        // Preço do frango assado com desconto
        $preco_unitario = 34.99;
        $preco_com_desconto = 31.49;
        $desconto_aplicado = 10.00;

        // Inserir ou atualizar o pedido no banco de dados
        $query = 'INSERT INTO frango_assado_pedidos (Nome, Telefone, Quantidade, Observacoes, PrecoUnitario, PrecoComDesconto, DescontoAplicado) 
                  VALUES (:nome, :telefone, :quantidade, :observacoes, :preco_unitario, :preco_com_desconto, :desconto_aplicado)';

        $params = [
            'nome' => $nome,
            'telefone' => $telefone,
            'quantidade' => $quantidade,
            'observacoes' => $observacoes,
            'preco_unitario' => $preco_unitario,
            'preco_com_desconto' => $preco_com_desconto,
            'desconto_aplicado' => $desconto_aplicado,
        ];

        $stmt = $this->conexao->prepare($query);
        $stmt->execute($params);
        $id_pedido = $this->conexao->lastInsertId();

        // Buscar os dados do pedido inserido
        $query = 'SELECT * FROM frango_assado_pedidos WHERE IdPedido = :idPedido';
        $stmt = $this->conexao->prepare($query);
        $stmt->execute(['idPedido' => $id_pedido]);

        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }
}
