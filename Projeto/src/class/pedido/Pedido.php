<?php

require_once __DIR__.'/../Conexao.php';
require_once __DIR__.'/../autenticacao/Autentica.php';

// Iniciar sessão caso esteja inativa
if (session_status() != PHP_SESSION_ACTIVE) {
    session_start();
}

class Pedido 
{
    protected Conexao $conexao;

    protected Autentica $autentica;

    public function __construct() {
        $this->conexao = new Conexao();
        $this->autentica = new Autentica();
    }

    public function cadastrar(
        int $cliente, 
        string $data_pedido, 
        float $valor_total,
        string $data_retirada = null,
        int $id = null
    ): array {
        $query = 'INSERT INTO pedidos (IdPessoa, DataPedido, DataRetirada, ValorTotal) 
                  VALUES (:cliente, :data_pedido, :data_retirada, :valor_total)';
        $params = [
            'cliente' => $cliente,
            'data_pedido' => $data_pedido,
            'data_retirada' => $data_retirada,
            'valor_total' => $valor_total
        ];
    
        if (!empty($id)) {
            $query = 'UPDATE pedidos 
                      SET IdPessoa = :cliente, 
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
        // Cancela o pedido
        $query = <<<SQL

        UPDATE pedidos
        SET Status = 'Cancelado'
        WHERE IdPedido = :id
SQL;
        $stmt = $this->conexao->prepare($query);
        $stmt->execute(['id' => $id]);

        // Restaura o estoque
        $query = <<<SQL

        UPDATE produtos p
        JOIN pedido_produtos pp ON p.IdProduto = pp.IdProduto
        SET p.Estoque = p.Estoque + pp.Quantidade
        WHERE pp.IdPedido = :idpedido;
SQL;
        $stmt = $this->conexao->prepare($query);
        $stmt->execute(['idpedido' => $id]);

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

    public function checkout(array $produtos): array {
        $_SESSION['pedido_produtos'] = $produtos;

        return ['redirecionar_url' => 'checkout.php'];
    }

    public function confirmar(
        int $id_cliente,
        string $metodo_pagamento,
        float $total,
        array $produtos,
        bool $sem_entrega=false,
        string $endereco=null,
        string $data_entrega=null,
    ): array {
        
        if (!$sem_entrega) {
            if (empty($endereco)) {
                throw new ValidacaoException('Endereço de entrega obrigatório');
            }
            if (empty($data_entrega)) {
                throw new ValidacaoException('Data de entrega obrigatória');
            }
        } else {
            $endereco = null;
            $data_entrega = null;
        }

        // Criar pedido
        $sql = <<<SQL

        INSERT INTO pedidos (IdPessoa, MetodoPagamento, ValorTotal, EnderecoEntrega, DataAgendada)
        VALUES (:id_cliente, :metodo_pagamento, :total, :endereco, :data_entrega)
SQL;

        $stmt = $this->conexao->prepare($sql);
        $stmt->execute([
            'id_cliente' => $id_cliente,
            'metodo_pagamento' => $metodo_pagamento,
            'total' => $total,
            'endereco' => $endereco,
            'data_entrega' => $data_entrega
        ]);

        $id_pedido = $this->conexao->lastInsertId();

        // Adicionar itens do pedido
        $sql = <<<SQL

        INSERT INTO pedido_produtos (IdPedido, IdProduto, Quantidade, PrecoUnitario)
        VALUES (:id_pedido, :id_produto, :quantidade, :preco_unitario)
SQL;
        $stmt = $this->conexao->prepare($sql);
        foreach ($produtos as $produto) {
            $stmt->execute([
                'id_pedido' => $id_pedido,
                'id_produto' => $produto['IdProduto'],
                'quantidade' => $produto['Quantidade'],
                'preco_unitario' => $produto['Preco']
            ]);
        }


        // Atualizar estoque
        $sql = <<<SQL
        
        UPDATE produtos
        SET Estoque = Estoque - :quantidade
        WHERE IdProduto = :id_produto
SQL;
        $stmt = $this->conexao->prepare($sql);

        foreach ($produtos as $produto) {
         
            $stmt->execute([
                'quantidade' => $produto['Quantidade'],
                'id_produto' => $produto['IdProduto']
            ]);
        }

        // Limpar carrinho
        unset($_SESSION['pedido_produtos']);

        return ['redirecionar_url' => "pedido_realizado.php?pedido={$id_pedido}"];
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

    public function listaItensCheckout(): array {
        $produtos = $_SESSION['pedido_produtos'] ?? [];

        // Gerar placeholders para a consulta SQL
        $placeholders = join(', ', array_fill(0, count($produtos), '(?, ?)'));
        

        $query = <<<SQL

        WITH checkout(idproduto, quantidade) AS (VALUES $placeholders)

        SELECT 
            p.*, 
            LEAST(p.estoque, c.quantidade) AS Quantidade,
            (COALESCE(p.PrecoComDesconto, p.Preco) * LEAST(p.estoque, c.quantidade)) AS PrecoTotal
        FROM checkout c
        JOIN VW_PRODUTOS_ATIVOS p ON p.idproduto = c.idproduto
        WHERE p.estoque > 0
SQL;

        // Preparar a consulta
        $stmt = $this->conexao->prepare($query);

        // Associar os valores aos placeholders (idproduto e quantidade)
        $params = [];
        foreach ($produtos as $produto) {
            $params[] = $produto['IdProduto'];
            $params[] = $produto['Quantidade'];
        }

        // Executar a consulta
        $stmt->execute($params);
        $produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Calcular o total do carrinho
        $total = 0;
        foreach ($produtos as $produto) {
            $total += $produto['PrecoTotal'];
        }
        
        return ['valor_total' => $total, 'produtos' => $produtos];
    }
}
