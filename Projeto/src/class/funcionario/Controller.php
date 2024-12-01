<?php

require_once __DIR__.'/Funcionario.php';  // Classe Funcionario
require_once __DIR__.'/../BaseController.php';

class FuncionarioController extends BaseController
{
    private Funcionario $funcionario;  // Instância da classe Funcionario

    public function __construct() 
    {
        parent::__construct();
        
        $this->funcionario = new Funcionario();  // Inicialização da classe Funcionario
    }

    public function cadastrarFuncionario(): array 
    {
        $retorno = $this->realizarAcao([$this->funcionario, 'cadastrar'], [
            'nome' => [
                'filter' => FILTER_DEFAULT, 
                'erro' => 'O nome do funcionário é obrigatório e não pode estar em branco.'
            ],
            'email' => [
                'filter' => FILTER_VALIDATE_EMAIL, 
                'erro' => 'O email deve ser um endereço de email válido.',
                'obrigatorio' => true
            ],
            'telefone' => [
                'filter' => FILTER_DEFAULT, 
                'erro' => 'O telefone do funcionário é obrigatório.',
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
            'mensagem' => 'Funcionário cadastrado com sucesso!', 
            'dados' => $retorno
        ];
    }

    public function atualizarFuncionario(): array 
    {
        $retorno = $this->realizarAcao([$this->funcionario, 'atualizar'], [
            'id' => [
                'filter' => FILTER_DEFAULT, 
                'erro' => 'O código do funcionário é obrigatório.'
            ],
            'nome' => [
                'filter' => FILTER_DEFAULT, 
                'erro' => 'O nome do funcionário é obrigatório e não pode estar em branco.'
            ],
            'email' => [
                'filter' => FILTER_VALIDATE_EMAIL, 
                'erro' => 'O email deve ser um endereço de email válido.',
                'obrigatorio' => false
            ],
            'telefone' => [
                'filter' => FILTER_DEFAULT, 
                'erro' => 'O telefone do funcionário é obrigatório.',
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
            'mensagem' => 'Funcionário atualizado com sucesso!', 
            'dados' => $retorno
        ];
    }

    public function excluirFuncionario(): array 
    {
        $retorno = $this->realizarAcao([$this->funcionario, 'excluir'], [
            'id' => [
                'filter' => FILTER_DEFAULT, 
                'erro' => 'O código do funcionário é obrigatório.'
            ]
        ]);

        return [
            'status' => 'ok', 
            'mensagem' => 'Funcionário excluído com sucesso!', 
            'dados' => $retorno
        ];
    }

    // Função para listar todos os funcionários
    public function listarFuncionarios(): array 
    {
        $funcionarios = $this->funcionario->listarTudo();

        return [
            'status' => 'ok',
            'dados' => $funcionarios
        ];
    }
}