<?php

require_once __DIR__.'/Funcionario.php';  // Classe Funcionario
require_once __DIR__.'/../BaseController.php';

class FuncionarioController extends BaseController
{
    private Funcionario $funcionario;  // Instância da classe Funcionario

    /**
     * Construtor da classe FuncionarioController.
     * 
     * Inicializa a classe Funcionario e chama o construtor da classe pai BaseController.
     */
    public function __construct() 
    {
        parent::__construct();
        
        $this->funcionario = new Funcionario();  // Inicialização da classe Funcionario
    }

    /**
     * Realiza o cadastro de um novo funcionário.
     * 
     * @return array Um array contendo o status da operação, a mensagem de retorno e os dados do funcionário cadastrado.
     */
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

    /**
     * Atualiza um funcionário existente.
     * 
     * @return array Um array contendo o status da operação, a mensagem de retorno e os dados do funcionário atualizado.
     */
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

    /**
     * Exclui um funcionário existente.
     * 
     * @return array Um array contendo o status da operação, a mensagem de retorno e os dados relacionados à exclusão.
     */
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

    /**
     * Retorna todos os funcionários ativos no banco de dados.
     *
     * Os funcionários são retornados em ordem alfabética.
     *
     * @return array Um array com os dados dos funcionários.
     */
    public function listarFuncionarios(): array 
    {
        $funcionarios = $this->funcionario->listarTudo();

        return [
            'status' => 'ok',
            'dados' => $funcionarios
        ];
    }
}