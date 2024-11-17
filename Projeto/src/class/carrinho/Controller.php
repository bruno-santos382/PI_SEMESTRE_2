<?php

require_once __DIR__.'/Carrinho.php';
require_once __DIR__.'/../BaseController.php';

class CarrinhoController extends BaseController
{
    private Carrinho $carrinho;

    public function __construct() 
    {
        parent::__construct();
        
        $this->carrinho = new Carrinho();
    }

    public function adicionarProduto(): array
    {
        $retorno = $this->realizarAcao([$this->carrinho, 'adicionar'], [
            'id_produto' => ['filter' => FILTER_VALIDATE_INT, 'erro' => 'O código do produto é obrigatório e deve ser um número inteiro.']
        ]);

        return [
            'status' => 'ok', 
            'mensagem' => 'Produto adicionado ao carrinho com sucesso!', 
            'dados' => $retorno
        ];
    }
}