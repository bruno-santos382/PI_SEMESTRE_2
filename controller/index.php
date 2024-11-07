<?php

require '../class/Produto.php';

header('Content-Type: application/json');

try {
    $rota = filter_input(INPUT_GET, 'route', FILTER_DEFAULT);

    $produto = new Produto();
    
    $funcao = [
        'produto/cadastrar' => [$produto, 'cadastrar'],
        'produto/atualizar' => [$produto, 'atualizar'],
        'produto/excluir' => [$produto, 'excluir'],
    ];
    
    if (isset($funcao[$rota])) {
        $dados = call_user_func($funcao[$rota]);
        echo json_encode($dados);
    }
} catch (\Exception $e) {
    echo json_encode(['erro' => $e->getMessage()]);
}
