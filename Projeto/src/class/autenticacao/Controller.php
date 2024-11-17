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
        $this->realizarAcao([$this->autentica, 'login'], [
            'login' => ['filter' => FILTER_DEFAULT, 'erro' => 'O campo login é obrigatório.'],
            'senha' => ['filter' => FILTER_DEFAULT, 'erro' => 'A senha é obrigatória.'],
        ]);

        return [
            'status' => 'ok', 
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
}
