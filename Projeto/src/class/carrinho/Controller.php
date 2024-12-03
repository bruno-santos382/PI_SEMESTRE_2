<?php

require_once __DIR__.'/Carrinho.php';
require_once __DIR__.'/../BaseController.php';

class CarrinhoController extends BaseController
{
    // Instância da classe Carrinho
    private Carrinho $carrinho;

    /**
     * Constroi um novo objeto CarrinhoController.
     * 
     * Ao construir um novo objeto, o construtor da classe pai (BaseController) é chamado, 
     * e o objeto Carrinho é instanciado.
     */
    public function __construct() 
    {
        parent::__construct();
        
        $this->carrinho = new Carrinho();
    }

    
    /**
     * Adiciona um produto ao carrinho.
     * 
     * O parâmetro 'id_produto' é obrigatório e deve ser um número inteiro.
     * Caso o produto ainda não esteja no carrinho, ele é adicionado com quantidade 1.
     * Caso contrário, a quantidade do produto no carrinho é incrementada em 1.
     * 
     * @return array Um array com as chaves 'status', 'mensagem' e 'dados'. 
     *               'status' é uma string que pode ter o valor 'ok' ou 'erro'.
     *               'mensagem' é uma string que pode ser exibida ao usuário.
     *               'dados' é um array com as chaves 'id_produto' e 'quantidade'.
     *               'id_produto' é o ID do produto adicionado ou removido do carrinho.
     *               'quantidade' é a quantidade do produto no carrinho.
     */
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

    /**
     * Remove um produto do carrinho.
     * 
     * O parâmetro 'id_produto' é obrigatório e deve ser um número inteiro.
     * Caso o produto esteja no carrinho, ele é removido.
     * 
     * @return array Um array com as chaves 'status', 'mensagem' e 'dados'. 
     *               'status' é uma string que pode ter o valor 'ok' ou 'erro'.
     *               'mensagem' é uma string que pode ser exibida ao usuário.
     *               'dados' é um array com as chaves 'id_produto' e 'quantidade'.
     *               'id_produto' é o ID do produto removido do carrinho.
     *               'quantidade' é a quantidade do produto no carrinho.
     */
    public function removerProduto(): array
    {
        $retorno = $this->realizarAcao([$this->carrinho, 'remover'], [
            'id_produto' => ['filter' => FILTER_VALIDATE_INT, 'erro' => 'O código do produto é obrigatório e deve ser um número inteiro.']
        ]);

        return [
            'status' => 'ok', 
            'mensagem' => 'Produto removido do carrinho com sucesso!', 
            'dados' => $retorno
        ];
    }

    /**
     * Esvazia o carrinho, removendo todos os produtos.
     * 
     * @return array Um array com as chaves 'status', 'mensagem' e 'dados'. 
     *               'status' é uma string que pode ter o valor 'ok' ou 'erro'.
     *               'mensagem' é uma string que pode ser exibida ao usuário.
     *               'dados' é um array com as chaves 'id_produto' e 'quantidade'.
     *               'id_produto' é o ID do produto removido do carrinho.
     *               'quantidade' é a quantidade do produto no carrinho.
     */
    public function esvaziarCarrinho(): array
    {
        $retorno = $this->realizarAcao([$this->carrinho, 'esvaziar']);

        return [
            'status' => 'ok', 
            'mensagem' => 'Todos os produtos foram removidos do carrinho com sucesso!', 
            'dados' => $retorno
        ];
    }
}