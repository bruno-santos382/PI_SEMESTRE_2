<?php

require_once __DIR__.'/Cliente.php';  // Classe Cliente
require_once __DIR__.'/../BaseController.php';

class ClienteController extends BaseController
{
    private Cliente $cliente;  // Instância da classe Cliente

    public function __construct() 
    {
        parent::__construct();
        
        $this->cliente = new Cliente();  // Inicialização da classe Cliente
    }

    public function cadastrarCliente(): array 
    {
        $retorno = $this->realizarAcao([$this->cliente, 'cadastrar'], [
            'nome' => [
                'filter' => FILTER_DEFAULT, 
                'erro' => 'O nome do cliente é obrigatório e não pode estar em branco.'
            ],
            'email' => [
                'filter' => FILTER_VALIDATE_EMAIL, 
                'erro' => 'O email deve ser um endereço de email válido.',
                'obrigatorio' => true
            ],
            'telefone' => [
                'filter' => FILTER_DEFAULT, 
                'erro' => 'O telefone do cliente é obrigatório.',
                'obrigatorio' => true
            ],
            'id_usuario' => [
                'filter' => FILTER_VALIDATE_INT, 
                'erro' => 'O código do usuário é obrigatório.',
                'obrigatorio' => false
            ]
        ]);

        return [
            'status' => 'ok', 
            'mensagem' => 'Cliente cadastrado com sucesso!', 
            'dados' => $retorno
        ];
    }

    public function atualizarCliente(): array 
    {
        $retorno = $this->realizarAcao([$this->cliente, 'atualizar'], [
            'id' => [
                'filter' => FILTER_DEFAULT, 
                'erro' => 'O código do cliente é obrigatório.'
            ],
            'nome' => [
                'filter' => FILTER_DEFAULT, 
                'erro' => 'O nome do cliente é obrigatório e não pode estar em branco.'
            ],
            'email' => [
                'filter' => FILTER_VALIDATE_EMAIL, 
                'erro' => 'O email deve ser um endereço de email válido.',
                'obrigatorio' => false
            ],
            'telefone' => [
                'filter' => FILTER_DEFAULT, 
                'erro' => 'O telefone do cliente é obrigatório.',
                'obrigatorio' => false
            ],
            'id_usuario' => [
                'filter' => FILTER_VALIDATE_INT, 
                'erro' => 'O código do usuário é obrigatório.',
                'obrigatorio' => false
            ]
        ]);

        return [
            'status' => 'ok', 
            'mensagem' => 'Cliente atualizado com sucesso!', 
            'dados' => $retorno
        ];
    }

    public function excluirCliente(): array 
    {
        $retorno = $this->realizarAcao([$this->cliente, 'excluir'], [
            'id' => [
                'filter' => FILTER_DEFAULT, 
                'erro' => 'O código do cliente é obrigatório.'
            ]
        ]);

        return [
            'status' => 'ok', 
            'mensagem' => 'Cliente excluído com sucesso!', 
            'dados' => $retorno
        ];
    }

    // Função para listar todos os clientes
    public function listarClientes(): array 
    {
        $clientes = $this->cliente->listarTudo();

        return [
            'status' => 'ok',
            'dados' => $clientes
        ];
    }
}
