<?php

require_once __DIR__.'/Pedido.php';
require_once __DIR__.'/../BaseController.php';

class PedidoController extends BaseController
{
    private Pedido $pedido;

    public function __construct() 
    {
        parent::__construct();
        
        $this->pedido = new Pedido();
    }


    public function cadastrarPedido(): array
    {
        $retorno = $this->realizarAcao([$this->pedido, 'cadastrar'], [
            'id' => ['filter' => FILTER_VALIDATE_INT, 'obrigatorio' => false],
            'cliente' => ['filter' => FILTER_VALIDATE_INT, 'erro' => 'O código do cliente é obrigatório.'],
            'data_pedido' => [
                'filter' => FILTER_CALLBACK,
                'options' => function ($value) {
                    $dt = DateTime::createFromFormat('Y-m-d', $value);
                    return $dt && $dt->format('Y-m-d') === $value ? $value : null;
                },
                'erro' => 'A data do pedido é obrigatória.'
            ],
            'data_retirada' => [
                'filter' => FILTER_CALLBACK,
                'options' => function ($value) {
                    $dt = DateTime::createFromFormat('Y-m-d', $value);
                    return $dt && $dt->format('Y-m-d') === $value ? $value : null;
                },
                'erro' => 'A data do pedido é obrigatória.',
                'obrigatorio' => false,
            ],
            'valor_total' => ['filter' => FILTER_VALIDATE_FLOAT, 'obrigatorio' => false],
            'status' => ['filter' => FILTER_VALIDATE_INT, 'obrigatorio' => false]
        ]);

        return [
            'status' => 'ok', 
            'mensagem' => 'Cadastro efetuado com sucesso!', 
            'dados' => $retorno
        ];
    }

    public function cancelarPedido(): array 
    {
        $retorno = $this->realizarAcao([$this->pedido, 'cancelar'], [
            'id' => ['filter' => FILTER_VALIDATE_INT, 'erro' => 'O código do pedido é obrigatório.'] 
        ]);

        return [
            'status' => 'ok', 
            'mensagem' => 'Pedido cancelado com sucesso!', 
            'dados' => $retorno
        ];
    }

    public function finalizarPedido(): array 
    {
        $retorno = $this->realizarAcao([$this->pedido, 'finalizar'], [
            'id' => ['filter' => FILTER_VALIDATE_INT, 'erro' => 'O código do pedido é obrigatório.'] 
        ]);

        return [
            'status' => 'ok', 
            'mensagem' => 'Pedido cancelado com sucesso!', 
            'dados' => $retorno
        ];
    }

    public function listaItensPedido(): array
    {
        $retorno = $this->realizarAcao([$this->pedido, 'listaItensPedido'], [
            'id' => ['filter' => FILTER_VALIDATE_INT, 'erro' => 'O código do pedido é obrigatório.'] 
        ]);

        return [
            'status' => 'ok', 
            'dados' => $retorno
        ];
    }

    public function realizarCheckout(): array
    {
        $retorno = $this->realizarAcao([$this->pedido, 'checkout'], [
            'produtos' => [
                'flags' => FILTER_REQUIRE_ARRAY,
                'erro' => 'Selecione ao menos um produto.'
            ]
        ]);

        return [
            'status' => 'ok', 
            'dados' => $retorno
        ];
    }

    public function confirmarPedido(): array 
    {
        $retorno = $this->realizarAcao([$this->pedido, 'confirmar'], [
            'id_cliente' => ['filter' => FILTER_VALIDATE_INT, 'erro' => 'O código do cliente é obrigatório.'],
            'metodo_pagamento' => ['filter' => FILTER_DEFAULT, 'erro' => 'Método de pagamento inválido.'],
            'sem_entrega' => ['filter' => FILTER_VALIDATE_BOOLEAN, 'erro' => 'Campo de entrega inválido.', 'obrigatorio' => false],
            'endereco' => ['filter' => FILTER_DEFAULT, 'erro' => 'Endereço inválido.', 'obrigatorio' => false],
            'data_entrega' => [
                'filter' => FILTER_CALLBACK,
                'options' => function ($value) {
                    $dt = DateTime::createFromFormat('Y-m-d', $value);
                    return $dt && $dt->format('Y-m-d') === $value ? $value : null;
                },
                'erro' => 'A data de entrega é obrigatória.',
                'obrigatorio' => false
            ],
            'produtos' => [
                'flags' => FILTER_REQUIRE_ARRAY, 
                'erro' => 'Selecione ao menos um produto.'
            ],
            'total' => ['filter' => FILTER_VALIDATE_FLOAT, 'erro' => 'Total inválido.'],
        ]);
        
        return [
            'status' => 'ok', 
            'dados' => $retorno
        ];
    }
}
