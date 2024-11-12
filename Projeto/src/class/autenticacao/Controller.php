<?php

require_once __DIR__.'/Autentica.php'; 
require_once __DIR__.'/../BaseController.php';

class AutenticaController extends BaseController
{
    private Autentica $autentica;

    public function __construct() 
    {
        parent::__construct();
        
        $this->autentica = new Autentica();
    }

    public function login(): array
    {
        return $this->realizarAcao([$this->autentica, 'login'], [
            'usuario' => ['filter' => FILTER_DEFAULT, 'erro' => 'O nome de usuário é obrigatório.'],
            'senha' => ['filter' => FILTER_DEFAULT, 'erro' => 'A senha é obrigatória.'],
        ]);
    }

    public function logout(): array
    {
        return $this->realizarAcao([$this->autentica, 'logout']);
    }
}