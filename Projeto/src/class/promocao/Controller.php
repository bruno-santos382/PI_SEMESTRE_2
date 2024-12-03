<?php

require_once __DIR__.'/Promocao.php';
require_once __DIR__.'/../BaseController.php';

class PromocaoController extends BaseController
{
    private Promocao $promocao;

    /**
     * Método construtor da classe.
     * 
     * Inicializa o pai e define a instância de Promocao.
     */
    public function __construct() 
    {
        parent::__construct();
        
        $this->promocao = new Promocao();
    }


    /**
     * Atualiza uma promoção existente.
     * 
     * A atualização de uma promoção requer os seguintes dados:
     * - id: O código da promoção é obrigatório.
     * - produto_id: O código do produto é obrigatório.
     * - desconto: O valor do desconto é opcional e deve ser um valor numérico válido.
     * - data_inicio: A data de início da promoção é obrigatória e deve ser uma data válida.
     * - data_fim: A data de fim da promoção é opcional e deve ser uma data válida.
     * 
     * @return array Retorna os dados da promoção atualizada.
     */
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

    /**
     * Exclui uma promoção existente.
     * 
     * A exclusão de uma promoção requer o código da promoção, que é obrigatório.
     * 
     * @return array Retorna os dados da promoção excluída.
     */
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