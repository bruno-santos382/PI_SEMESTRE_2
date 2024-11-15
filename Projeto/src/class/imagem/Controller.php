<?php

require_once __DIR__.'/Imagem.php';
require_once __DIR__.'/../BaseController.php';

class ImagemController extends BaseController
{
    private Imagem $imagem;

    public function __construct() 
    {
        parent::__construct();
        
        $this->imagem = new Imagem();
    }

    public function uploadImagem(): array
    {
        $retorno = $this->realizarAcao([$this->imagem, 'upload']);

        return [
            'status' => 'ok', 
            'mensagem' => 'Imagem adicionada com sucesso!', 
            'dados' => $retorno
        ];
    }

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