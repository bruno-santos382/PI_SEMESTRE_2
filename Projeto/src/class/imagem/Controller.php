<?php

require_once __DIR__.'/Imagem.php';
require_once __DIR__.'/../BaseController.php';

class ImagemController extends BaseController
{
    private Imagem $imagem;

    /**
     * Método construtor da classe.
     * 
     * Chama o construtor da classe pai e instancia o objeto Imagem.
     */
    public function __construct() 
    {
        parent::__construct();
        
        $this->imagem = new Imagem();
    }

    /**
     * Realiza o upload de uma imagem.
     * 
     * @return array Retorna um array com o status, mensagem e dados adicionais.
     */
    public function uploadImagem(): array
    {
        $retorno = $this->realizarAcao([$this->imagem, 'upload']);

        return [
            'status' => 'ok', 
            'mensagem' => 'Imagem adicionada com sucesso!', 
            'dados' => $retorno
        ];
    }

    /**
     * Exclui uma imagem existente.
     * 
     * @return array Um array contendo o status da operação, a mensagem de retorno e dados adicionais.
     */
    public function excluirImagem(): array 
    {
        $retorno = $this->realizarAcao([$this->imagem, 'excluir'], [
            'id' => ['filter' => FILTER_DEFAULT, 'erro' => 'O código do imagem é obrigatório.'] ]);

        return [
            'status' => 'ok', 
            'mensagem' => 'Imagem excluída com sucesso!', 
            'dados' => $retorno
        ];
    }
}