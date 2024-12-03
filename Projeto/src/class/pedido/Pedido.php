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

    /**
     * Construtor da classe Pedido.
     * 
     * Inicializa uma nova conexão com o banco de dados e a instância de autenticação.
     */
    public function __construct() {
        $this->conexao = new Conexao();
        $this->autentica = new Autentica();
    }

    /**
     * Cadastra um novo pedido ou edita um existente.
     * 
     * @param int $cliente O código do cliente.
     * @param string $data_pedido A data do pedido.
     * @param float $valor_total O valor total do pedido.
     * @param string $data_retirada A data de retirada do pedido.
     * @param int $id O código do pedido. Se não for informado, um novo pedido será criado.
     * 
     * @return array O pedido cadastrado ou editado.
     */
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
    
    /**
     * Cancela um pedido e restaura o estoque dos produtos.
     * 
     * @param int $id O código do pedido a ser cancelado.
     * 
     * @return array Os dados do pedido cancelado.
     */
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

    /**
     * Finaliza um pedido e marca como 'Finalizado'.
     * 
     * @param int $id O código do pedido a ser finalizado.
     * 
     * @return array Os dados do pedido finalizado.
     */
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

    /**
     * Armazena os produtos no carrinho de compras e redireciona para a página de checkout.
     * 
     * @param array $produtos A lista de produtos a serem armazenados no carrinho.
     * 
     * @return array Um array com a chave 'redirecionar_url' com o valor da URL para a qual o sistema deve redirecionar.
     */
    public function checkout(array $produtos): array {
        $_SESSION['pedido_produtos'] = $produtos;

        return ['redirecionar_url' => 'checkout.php'];
    }

    /**
     * Confirma um pedido, criando um novo registro no banco de dados e atualizando o estoque.
     *
     * @param int $id_cliente O identificador do cliente que está realizando o pedido.
     * @param string $metodo_pagamento O método de pagamento utilizado para o pedido.
     * @param float $total O valor total do pedido.
     * @param array $produtos Lista de produtos incluídos no pedido, cada um com IdProduto, Quantidade e Preco.
     * @param bool $sem_entrega Define se o pedido não requer entrega. Se true, endereço e data de entrega são ignorados.
     * @param string|null $endereco O endereço de entrega do pedido, se aplicável.
     * @param string|null $data_entrega A data agendada para entrega, se aplicável.
     *
     * @throws ValidacaoException Se o endereço ou a data de entrega estiverem ausentes quando a entrega for necessária.
     *
     * @return array Retorna um array com a URL de redirecionamento após a confirmação do pedido.
     */
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

    /**
     * Busca um pedido pelo seu identificador único.
     * 
     * @param int $id Identificador único do pedido.
     * 
     * @return array Retorna os dados do pedido.
     */
    public function buscaPorId(int $id): array {
        $query = <<<SQL

        SELECT * FROM VW_PEDIDOS_CLIENTE
        WHERE idpedido = :idpedido
SQL;

        $stmt = $this->conexao->prepare($query);
        $stmt->execute(['idpedido' => $id]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    /**
     * Consulta os pedidos em andamento.
     * 
     * @return array Retorna os dados dos pedidos em andamento.
     */
    public function listarPedidosEmAndamento(): array {
        $query = <<<SQL

        SELECT * FROM VW_PEDIDOS_CLIENTE
        WHERE Status = 'Em Andamento'
        ORDER BY DataPedido, DataRetirada
SQL;

        $stmt = $this->conexao->query($query);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
    
    /**
     * Consulta os pedidos finalizados.
     * 
     * @return array Retorna os dados dos pedidos finalizados.
     */
    public function listarPedidosFinalizados(): array {
        $query = <<<SQL

        SELECT * FROM VW_PEDIDOS_CLIENTE
        WHERE Status = 'Finalizado'
        ORDER BY DataPedido, DataRetirada
SQL;

        $stmt = $this->conexao->query($query);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Consulta os pedidos cancelados.
     * 
     * @return array Retorna os dados dos pedidos cancelados.
     */
    public function listarPedidosCancelados(): array {
        $query = <<<SQL

        SELECT * FROM VW_PEDIDOS_CLIENTE
        WHERE Status = 'Cancelado'
        ORDER BY DataPedido, DataRetirada
SQL;

        $stmt = $this->conexao->query($query);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Retorna os itens de um pedido, incluindo a quantidade e o valor total de cada item.
     * 
     * @param int $id Identificador único do pedido.
     * 
     * @return array Retorna um array com a chave 'itens' contendo os dados dos itens do pedido,
     *               e a chave 'total' com o valor total do pedido.
     */
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

    /**
     * Retorna um array com os dados dos produtos do carrinho, incluindo
     * a quantidade e o valor total de cada item.
     * 
     * @return array Retorna um array com as chaves 'valor_total' e 'produtos'.
     *               'valor_total'   um float com o valor total do carrinho.
     *               'produtos'      um array de arrays com os produtos do carrinho,
     *                               cada um contendo as chaves 'IdProduto', 'Nome',
     *                               'Preco', 'Quantidade', 'PrecoTotal' e 'Estoque'.
     */
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
