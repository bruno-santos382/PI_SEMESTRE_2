<?php

require_once __DIR__.'/Produto.php';
require_once __DIR__.'/../BaseController.php';

class ProdutoController extends BaseController
{
    private Produto $produto;

    public function __construct() 
    {
        parent::__construct();
        
        $this->produto = new Produto();
    }

    public function cadastrarProduto(): array
    {
        $retorno = $this->realizarAcao([$this->produto, 'cadastrar'], [
            'nome' => ['filter' => FILTER_DEFAULT, 'erro' => 'O nome do produto é obrigatório e não pode em branco.'],
            'preco' => ['filter' => FILTER_VALIDATE_FLOAT, 'erro' => 'O preço do produto é obrigatório e deve ser um valor numérico válido.'],
            'marca' => ['filter' => FILTER_DEFAULT, 'erro' => 'A marca do produto é obrigatória e não pode estar vazia.'],
            'estoque' => ['filter' => FILTER_VALIDATE_INT, 'erro' => 'A quantidade em estoque do produto é obrigatória e deve ser um número inteiro válido.'],
        ]);

        return ['status' => 'ok', 'mensagem' => 'Produto cadastrado com sucesso!', 'dados' => $retorno];
    }

    public function atualizarProduto(): array 
    {
        $retorno = $this->realizarAcao([$this->produto, 'atualizar'], [
            'id' => ['filter' => FILTER_DEFAULT, 'erro' => 'O código do produto é obrigatório.'],
            'nome' => ['filter' => FILTER_DEFAULT, 'erro' => 'O nome do produto é obrigatório  e não pode estar em branco.'],
            'preco' => ['filter' => FILTER_VALIDATE_FLOAT, 'erro' => 'O preço do produto é obrigatório e deve ser um valor numérico válido.'],
            'marca' => ['filter' => FILTER_DEFAULT, 'erro' => 'A marca do produto é obrigatória e não pode estar vazia.'],
            'estoque' => ['filter' => FILTER_VALIDATE_INT, 'erro' => 'A quantidade em estoque do produto é obrigatória e deve ser um número inteiro válido.'],
        ]);

        return ['status' => 'ok', 'mensagem' => 'Produto atualizado com sucesso!', 'dados' => $retorno];
    }

    public function excluirProduto(): array 
    {
        $retorno = $this->realizarAcao([$this->produto, 'excluir'], [
            'id' => ['filter' => FILTER_DEFAULT, 'erro' => 'O código do produto é obrigatório.'] ]);

        return ['status' => 'ok', 'mensagem' => 'Produto excluído com sucesso!', 'dados' => $retorno];
    }

    public function buscarProduto(): array
    {
        $retorno = $this->realizarAcao([$this->produto, 'buscar'], [
            'termo' => ['filter' => FILTER_DEFAULT, 'erro' => 'O termo da busca é obrigatório.']
        ]);

        return ['status' => 'ok', 'dados' => $retorno];
    }

    public function listarProdutos(): array
    {
        $retorno = $this->realizarAcao([$this->produto, 'listar']);
        
        return ['status' => 'ok', 'dados' => $retorno];
    }
}