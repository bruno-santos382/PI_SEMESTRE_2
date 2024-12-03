<?php

require_once __DIR__.'/Produto.php';
require_once __DIR__.'/../BaseController.php';

class ProdutoController extends BaseController
{
    private Produto $produto;

    /**
     * Método construtor da classe ProdutoController.
     */
    public function __construct() {
        parent::__construct();
        
        $this->produto = new Produto();
    }





    /**
     * Realiza o cadastro de um novo produto.
     * 
     * O cadastro de um produto requer os seguintes dados:
     * - nome: O nome do produto é obrigatório e não pode estar em branco.
     * - preco: O preço do produto é obrigatório e deve ser um valor numérico válido.
     * - marca: A marca do produto é obrigatória.
     * - categoria: A categoria do produto é obrigatória e deve ser um número inteiro.
     * - estoque: A quantidade em estoque do produto é obrigatória e deve ser um número inteiro válido.
     * - id_imagem: O ID da imagem do produto é opcional e deve ser um número inteiro maior que zero.
     * 
     * @return array Retorna os dados do produto cadastrado.
     */
    public function cadastrarProduto(): array
    {
        $retorno = $this->realizarAcao([$this->produto, 'cadastrar'], [
            'nome' => ['filter' => FILTER_DEFAULT, 'erro' => 'O nome do produto é obrigatório e não pode em branco.'],
            'preco' => ['filter' => FILTER_VALIDATE_FLOAT, 'erro' => 'O preço do produto é obrigatório e deve ser um valor numérico válido.'],
            'marca' => ['filter' => FILTER_DEFAULT, 'erro' => 'A marca do produto é obrigatória.'],
            'categoria' => ['filter' => FILTER_VALIDATE_INT, 'erro' => 'A categoria do produto é obrigatória e deve ser um número inteiro.'] ,
            'estoque' => ['filter' => FILTER_VALIDATE_INT, 'erro' => 'A quantidade em estoque do produto é obrigatória e deve ser um número inteiro válido.'],
            'id_imagem' => ['filter' => FILTER_VALIDATE_INT, 'flags' => FILTER_NULL_ON_FAILURE, 'options' => ['min_range' => 1], 'obrigatorio' => false]
        ]);

        return ['status' => 'ok', 'mensagem' => 'Produto cadastrado com sucesso!', 'dados' => $retorno];
    }

    /**
     * Atualiza um produto existente.
     * 
     * A atualização de um produto requer os seguintes dados:
     * - id: O código do produto é obrigatório.
     * - nome: O nome do produto é obrigatório e não pode estar em branco.
     * - preco: O preço do produto é obrigatório e deve ser um valor numérico válido.
     * - marca: A marca do produto é obrigatória.
     * - categoria: A categoria do produto é obrigatória e deve ser um número inteiro.
     * - estoque: A quantidade em estoque do produto é obrigatória e deve ser um número inteiro válido.
     * - id_imagem: O ID da imagem do produto é opcional.
     * 
     * @return array Retorna os dados do produto atualizado.
     */
    public function atualizarProduto(): array 
    {
        $retorno = $this->realizarAcao([$this->produto, 'atualizar'], [
            'id' => ['filter' => FILTER_DEFAULT, 'erro' => 'O código do produto é obrigatório.'],
            'nome' => ['filter' => FILTER_DEFAULT, 'erro' => 'O nome do produto é obrigatório  e não pode estar em branco.'],
            'preco' => ['filter' => FILTER_VALIDATE_FLOAT, 'erro' => 'O preço do produto é obrigatório e deve ser um valor numérico válido.'],
            'marca' => ['filter' => FILTER_DEFAULT, 'erro' => 'A marca do produto é obrigatória.'],
            'categoria' => ['filter' => FILTER_VALIDATE_INT, 'erro' => 'A categoria do produto é obrigatória e deve ser um número inteiro.'] ,
            'estoque' => ['filter' => FILTER_VALIDATE_INT, 'erro' => 'A quantidade em estoque do produto é obrigatória e deve ser um número inteiro válido.'],
            'id_imagem' => ['filter' => FILTER_VALIDATE_INT, 'obrigatorio' => false]
        ]);

        return ['status' => 'ok', 'mensagem' => 'Produto atualizado com sucesso!', 'dados' => $retorno];
    }

    /**
     * Exclui um produto existente.
     * 
     * A exclusão de um produto requer o código do produto, que é obrigatório.
     * 
     * @return array Retorna os dados do produto excluído.
     */
    public function excluirProduto(): array 
    {
        $retorno = $this->realizarAcao([$this->produto, 'excluir'], [
            'id' => ['filter' => FILTER_DEFAULT, 'erro' => 'O código do produto é obrigatório.'] ]);

        return ['status' => 'ok', 'mensagem' => 'Produto excluído com sucesso!', 'dados' => $retorno];
    }

    /**
     * Busca produtos pelo termo informado.
     * 
     * A busca de um produto requer o termo de busca, que é obrigatório.
     * 
     * @return array Retorna os dados dos produtos encontrados.
     */
    public function buscarProduto(): array
    {
        $retorno = $this->realizarAcao([$this->produto, 'buscar'], [
            'termo' => ['filter' => FILTER_DEFAULT, 'erro' => 'O termo da busca é obrigatório.']
        ]);

        return ['status' => 'ok', 'dados' => $retorno];
    }

    /**
     * Retorna todos os produtos existentes.
     * 
     * @return array Retorna os dados dos produtos.
     */
    public function listarProdutos(): array
    {
        $retorno = $this->realizarAcao([$this->produto, 'listar']);
        
        return ['status' => 'ok', 'dados' => $retorno];
    }
}