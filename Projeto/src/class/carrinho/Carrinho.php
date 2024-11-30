<?php

require_once __DIR__.'/../Conexao.php';
require_once __DIR__.'/../validacao/ValidacaoException.php';

// Iniciar sessão caso esteja inativa
if (session_status() != PHP_SESSION_ACTIVE) {
    session_start();
}

class Carrinho 
{
    protected Conexao $conexao;

    public function __construct() {
        $this->conexao = new Conexao();
    }
    
    public function adicionar(int $id_produto): void
    {        
        // Inicializa o carrinho se ainda não existir
        if (!isset($_SESSION['carrinho'])) {
            $_SESSION['carrinho'] = [];
        }

        // Procura pelo produto no carrinho
        foreach ($_SESSION['carrinho'] as &$item) {
            if ($item['IdProduto'] === $id_produto) {
                // Produto já está no carrinho, atualiza a quantidade
                $item['Quantidade'] += 1;
                return;
            }
        }

        // Se o produto não estiver no carrinho, adiciona como novo
        $_SESSION['carrinho'][] = [
            'IdProduto' => $id_produto,
            'Quantidade' => 1
        ];
    }

    public function remover(int $id_produto): void
    {
        // Procura pelo produto no carrinho
        foreach ($_SESSION['carrinho'] as $index => $item) {
            if ($item['IdProduto'] === $id_produto) {
                // Remove o produto do carrinho
                unset($_SESSION['carrinho'][$index]);
                return;
            }
        }
    }

    public function esvaziar(): void
    {
        unset($_SESSION['carrinho']);
    }

    public function obterItens(): array
    {        
        $produtos = $_SESSION['carrinho'] ?? [];

        if (empty($produtos)) {
            return ['valor_total' => 0, 'produtos' => []];
        }
        
        // Gerar placeholders para a consulta SQL
        $placeholders = join(', ', array_fill(0, count($produtos), '(?, ?)'));

        // Construir a query
        $query = <<<SQL
        WITH carrinho(idproduto, quantidade) AS (VALUES $placeholders)
        SELECT 
            p.*, 
            img.caminho AS Imagem, 
            c.quantidade AS Quantidade, 
            (COALESCE(p.PrecoComDesconto, p.Preco) * c.quantidade) AS PrecoTotal
        FROM 
            carrinho c
        JOIN 
            VW_PRODUTOS_ATIVOS p ON p.idproduto = c.idproduto
        LEFT JOIN 
            imagens img ON img.idimagem = p.idimagem;
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