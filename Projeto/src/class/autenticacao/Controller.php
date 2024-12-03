<?php

require_once __DIR__.'/Autentica.php'; 
require_once __DIR__.'/../BaseController.php';

class AutenticaController extends BaseController
{
    private Autentica $autentica;

    /**
     * Construtor da classe AutenticaController.
     */
    public function __construct() 
    {
        parent::__construct();
        $this->autentica = new Autentica();
    }

    /**
     * Realiza o login do usuário.
     * 
     * @return array Retorna um array com o status e mensagem de sucesso.
     * @throws ValidacaoException Caso as credenciais estejam inválidas.
     */
    public function login(): array
    {
        $retorno = $this->realizarAcao([$this->autentica, 'login'], [
            'login' => ['filter' => FILTER_DEFAULT, 'erro' => 'O campo login é obrigatório.'],
            'senha' => ['filter' => FILTER_DEFAULT, 'erro' => 'A senha é obrigatória.'],
        ]);

        return [
            'status' => 'ok', 
            'dados' => $retorno,
            'mensagem' => 'Login efetuado com sucesso!'
        ];
    }

    /**
     * Realiza o logout do usuário.
     * 
     * @return array Retorna um array com o status e mensagem de sucesso.
     */
    public function logout(): array
    {
        $this->realizarAcao([$this->autentica, 'logout']);

        return [
            'status' => 'ok', 
            'mensagem' => 'Logout efetuado com sucesso!'
        ];
    }

    /**
     * Registra um novo usuário.
     * 
     * @return array Retorna um array com o status, dados do registro e mensagem de sucesso.
     * @throws ValidacaoException Caso os dados fornecidos para registro sejam inválidos.
     */
    public function registrar(): array
    {
        $retorno = $this->realizarAcao([$this->autentica, 'registrar'], [
            'nome' => ['filter' => FILTER_DEFAULT, 'erro' => 'O campo nome é obrigatório.'],
            'email' => ['filter' => FILTER_VALIDATE_EMAIL, 'erro' => 'O campo email é obrigatório.'],
            'telefone' => ['filter' => FILTER_SANITIZE_NUMBER_INT, 'erro' => 'O campo telefone é obrigatório.'],
            'senha' => ['filter' => FILTER_DEFAULT, 'erro' => 'A senha é obrigatória.'],
            'confirmar_senha' => ['filter' => FILTER_DEFAULT, 'erro' => 'A confirmação da senha é obrigatória.']
        ]);

        return [
            'status' => 'ok', 

            'dados' => $retorno,
            'mensagem' => 'Cadastro efetuado com sucesso!'
        ];
    }

    
    /**
     * Recupera a senha do usuário.
     * 
     * @return array Retorna um array com o status, dados de recuperação de senha e mensagem de sucesso.
     * @throws ValidacaoException Caso o email fornecido seja inválido.
     */
    public function recuperarSenha(): array
    {
        $retorno = $this->realizarAcao([$this->autentica, 'recuperarSenha'], [
            'email' => ['filter' => FILTER_VALIDATE_EMAIL, 'erro' => 'O campo email é obrigatório.']
        ]);

        return [
            'status' => 'ok', 
            'dados' => $retorno,
            'mensagem' => 'Email enviado com sucesso!'
        ];
    }
}
