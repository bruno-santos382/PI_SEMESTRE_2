<?php

require_once __DIR__ . '/FrangoAssado.php';
require_once __DIR__ . '/../BaseController.php';

class FrangoAssadoController extends BaseController
{
    private FrangoAssado $frango_assado;

    /**
     * Construtor da classe FrangoAssadoController.
     * 
     * Cria uma inst ncia de FrangoAssado e a armazena em uma propriedade
     * protegida.
     */
    public function __construct() 
    {
        parent::__construct();
        
        $this->frango_assado = new FrangoAssado();
    }

    /**
     * Realiza um novo pedido de Frango Assado.
     * 
     * @return array Retorna os dados do pedido inserido/atualizado.
     */
    public function novoPedido(): array
    {
        $retorno = $this->realizarAcao([$this->frango_assado, 'novoPedido'], [
            'id' => [
                'filter' => FILTER_VALIDATE_INT,
                'obrigatorio' => false
            ],
            'nome' => [
                'filter' => FILTER_DEFAULT,
                'erro' => 'O nome completo é obrigatório.'
            ],
            'telefone' => [
                'filter' => FILTER_DEFAULT,
                'erro' => 'O telefone é obrigatório.'
            ],
            'quantidade' => [
                'filter' => FILTER_VALIDATE_INT,
                'options' => [
                    'min_range' => 1
                ],
                'erro' => 'A quantidade de frangos é obrigatória e deve ser um número positivo.'
            ],
            'observacoes' => [
                'filter' => FILTER_DEFAULT,
                'obrigatorio' => false
            ]
        ]);

        return [
            'status' => 'ok', 
            'mensagem' => 'Pedido de Frango Assado realizado com sucesso!', 
            'dados' => $retorno
        ];
    }

    /**
     * Cancela um pedido de Frango Assado.
     * 
     * @return array Retorna os dados do pedido cancelado.
     */
    public function cancelarPedido(): array 
    {
        $retorno = $this->realizarAcao([$this->frango_assado, 'cancelarPedido'], [
            'id' => ['filter' => FILTER_VALIDATE_INT, 'erro' => 'O código do pedido é obrigatório.'] 
        ]);

        return [
            'status' => 'ok', 
            'mensagem' => 'Pedido cancelado com sucesso!', 
            'dados' => $retorno
        ];
    }

    /**
     * Finaliza um pedido de Frango Assado.
     * 
     * @return array Retorna os dados do pedido finalizado.
     */
    public function finalizarPedido(): array 
    {
        $retorno = $this->realizarAcao([$this->frango_assado, 'finalizarPedido'], [
            'id' => ['filter' => FILTER_VALIDATE_INT, 'erro' => 'O código do pedido é obrigatório.'] 
        ]);

        return [
            'status' => 'ok', 
            'mensagem' => 'Pedido finalizado com sucesso!', 
            'dados' => $retorno
        ];
    }
}
