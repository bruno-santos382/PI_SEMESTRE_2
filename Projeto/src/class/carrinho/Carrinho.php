<?php

require_once __DIR__.'/../Conexao.php';
require_once __DIR__.'/../validacao/ValidacaoException.php';
require_once __DIR__.'/../autenticacao/Autentica.php';

// Iniciar sessão caso esteja inativa
if (session_status() != PHP_SESSION_ACTIVE) {
    session_start();
}

class Carrinho 
{
    protected Conexao $conexao;

    protected Autentica $autentica;

    public function __construct(Conexao $conexao=null, Autentica $autentica=null) {
        $this->conexao = $conexao ?? new Conexao();
        // Passar dependencias por construtor para evitar loop infinito
        // pois Autentica também instancia um  objeto Carrinho
        $this->autentica = $autentica ?? new Autentica($this->conexao, $this);
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

     // Carregar os produtos do carrinho na sessão
     public function carregar(): void 
     {
        $usuario = $this->autentica->usuarioLogado();

        $query = <<<SQL
        
        SELECT cp.* FROM carrinho c
        JOIN carrinho_produtos cp ON cp.IdCarrinho = c.IdCarrinho
        WHERE c.IdPessoa = ?
SQL;
        $stmt = $this->conexao->prepare($query);
        $stmt->execute([$usuario['id_pessoa']]);
        
        $_SESSION['carrinho'] = array_merge(
            $_SESSION['carrinho'] ?? [], 
            $stmt->fetchAll(PDO::FETCH_ASSOC)
        );
    }

    // Salvar o carrinho no banco de dados
    public function salvar(): void 
    {
        $usuario = $this->autentica->usuarioLogado();

        $query = "SELECT IdCarrinho FROM carrinho WHERE IdPessoa = ? LIMIT 1";
        $stmt = $this->conexao->prepare($query);
        $stmt->execute([$usuario['id_pessoa']]);
        $id_carrinho = $stmt->fetchColumn();

       if (!empty($id_carrinho))
       {
            // Limpar produtos do carrinho
            $query = "DELETE FROM carrinho_produtos WHERE IdCarrinho = ?";
            $stmt = $this->conexao->prepare($query);
            $stmt->execute([$id_carrinho]);
       }
       else
       {
            // Criar novo carrinho
            $query = "INSERT INTO carrinho (IdPessoa) VALUES (?)";
            $stmt = $this->conexao->prepare($query);
            $stmt->execute([$usuario['id_pessoa']]);
            $id_carrinho = $this->conexao->lastInsertId();
       }

        // Inserir produtos no carrinho
        $query = "INSERT INTO carrinho_produtos (IdCarrinho, IdProduto, Quantidade) VALUES (?, ?, ?)";
        $stmt = $this->conexao->prepare($query);

        foreach ($_SESSION['carrinho'] as $item) {
            $stmt->execute([
                $id_carrinho, 
                $item['IdProduto'], 
                $item['Quantidade']]
            );
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
            LEAST(p.estoque, c.quantidade) AS Quantidade,
            (COALESCE(p.PrecoComDesconto, p.Preco) * LEAST(p.estoque, c.quantidade)) AS PrecoTotal
        FROM carrinho c
        JOIN VW_PRODUTOS_ATIVOS p ON p.idproduto = c.idproduto
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