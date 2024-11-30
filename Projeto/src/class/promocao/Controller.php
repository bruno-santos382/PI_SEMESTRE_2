<?php

require_once __DIR__.'/Promocao.php';
require_once __DIR__.'/../BaseController.php';

class PromocaoController extends BaseController
{
    private Promocao $promocao;

    public function __construct() 
    {
        parent::__construct();
        
        $this->promocao = new Promocao();
    }

    public function cadastrarPromocao(): array
    {
        $retorno = $this->realizarAcao([$this->promocao, 'cadastrar'], [
            'id' => ['filter' => FILTER_VALIDATE_INT, 'obrigatorio' => false],
            'produto' => ['filter' => FILTER_VALIDATE_INT, 'erro' => 'O código do produto é obrigatório.'],
            'desconto' => ['filter' => FILTER_VALIDATE_FLOAT, 'obrigatorio' => false],
            'data_inicio' => [
                'filter' => FILTER_CALLBACK,
                'options' => function ($value) {
                    $dt = DateTime::createFromFormat('Y-m-d', $value);
                    return $dt && $dt->format('Y-m-d') === $value ? $value : false;
                },
                'erro' => 'A data de início da promoção é obrigatória.'
            ],
            'data_fim' => [
                'filter' => FILTER_CALLBACK,
                'options' => function ($value) {
                    $dt = DateTime::createFromFormat('Y-m-d', $value);
                    return $dt && $dt->format('Y-m-d') === $value ? $value : false;
                },
                'erro' => 'A data de término da promoção é obrigatória.'
            ]
        ]);

        return [
            'status' => 'ok', 
            'mensagem' => 'Cadastro efetuado com sucesso!', 
            'dados' => $retorno
        ];
    }

    public function excluirPromocao(): array 
    {
        $retorno = $this->realizarAcao([$this->promocao, 'excluir'], [
            'id' => ['filter' => FILTER_VALIDATE_INT, 'erro' => 'O código da promoção é obrigatório.'] 
        ]);

        return [
            'status' => 'ok', 
            'mensagem' => 'Promoção excluída com sucesso!', 
            'dados' => $retorno
        ];
    }
}