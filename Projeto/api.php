<?php

require_once __DIR__.'/src/includes/manipular_erros.php';
require_once __DIR__.'/src/class/produto/Controller.php';
require_once __DIR__.'/src/class/autenticacao/Controller.php';
require_once __DIR__.'/src/class/imagem/Controller.php';
require_once __DIR__.'/src/class/carrinho/Controller.php';
require_once __DIR__.'/src/class/validacao/ValidacaoException.php';

header('Content-Type: application/json');

try {
    $rota = filter_input(INPUT_GET, 'route', FILTER_DEFAULT);

    $produto = new ProdutoController();
    $autentica = new AutenticaController();
    $imagem = new ImagemController();
    $carrinho = new CarrinhoController();
    
    $funcao = [
        // Rotas de produto
        'produto/cadastrar' => [$produto, 'cadastrarProduto'],
        'produto/atualizar' => [$produto, 'atualizarProduto'],
        'produto/excluir' => [$produto, 'excluirProduto'],
        'produto/buscar' => [$produto, 'buscarProduto'],
        'produto/listar' => [$produto, 'listarProdutos'],

        // Rotas de autenticação
        'autentica/login' => [$autentica, 'login'],
        'autentica/logout' => [$autentica, 'logout'],

        // Rotas de imagem
        'imagem/upload' => [$imagem, 'uploadImagem'],
        'imagem/excluir' => [$imagem, 'excluirImagem'],

        // Rotas do carrinho
        'carrinho/adicionar' => [$carrinho, 'adicionarProduto'],

        // Rotas de categoria
        'categoria/cadastrar' => [$categoria, 'cadastrarCategoria'],
        'categoria/excluir' => [$categoria, 'excluirCategoria']
    ];
    
    if (!isset($funcao[$rota])) {
        throw new \Exception("Rota não definida: $rota");
    }

    $resposta = call_user_func($funcao[$rota]);
    echo json_encode($resposta);
} catch(ValidacaoException $e) {
    echo json_encode([
        'status' => 'erro', 
        'mensagem' => $e->getMessage()
    ]);
} catch (\Throwable $th) {
    $message = $th->getMessage();
    $file = $th->getFile();
    $line = $th->getLine();
    $stack_trace = $th->getTraceAsString();
    // Registrar o erro no arquivo de log
    trigger_error("Erro inesperado: $message em $file na linha $line\nPilha de chamadas:\n$stack_trace", E_USER_ERROR);
    
    echo json_encode([
        'status' => 'erro', 
        'mensagem' => 'Ocorreu um erro inesperado.'
    ]);
}
