<?php

require_once __DIR__.'/../Conexao.php';
require_once __DIR__.'/../validacao/ValidacaoException.php';

class Autentica 
{
    private Conexao $conexao;

    public function __construct()
    {
        $this->conexao = new Conexao();
    }
    
    public function login(string $login, string $senha): void
    {
        $query = "SELECT * FROM usuarios WHERE usuario = :login";

        // Caso o formato do login for um email
        if (filter_var($login, FILTER_VALIDATE_EMAIL)) {
            $query = "SELECT * FROM usuarios WHERE email = :login";
        } 
        // Caso o formato do login for um número de telefone
        else {
            $telefone = preg_replace('/[^\d]*/', '', $login); // Apaga todos os caracteres não numéricos
            if (strlen($telefone) >= 10) {
                $query = "SELECT * FROM usuarios WHERE telefone = :login";
                $login = $telefone;
            }
        } 

        $stmt = $this->conexao->prepare($query);
        $stmt->execute(['login' => $login]);
        $row = $stmt->fetch(Conexao::FETCH_OBJ);

        if (!$row || !password_verify($senha, $row->senha)) {
            throw new ValidacaoException('Usuario ou senha inválidos.');
        }

        $this->verificaSessao();
        session_regenerate_id(true);
        $_SESSION['usuario'] = array('id' => $row->idusuario);
    }

    public function logout(): void
    {
        $this->verificaSessao();
        unset($_SESSION['usuario']);
    }

    public function usuarioLogado(): array|false
    {
        return $_SESSION['usuario'] ?? false;
    }

    public function verificaLogin(): void
    {
        
    }

    private function verificaSessao(): void
    {
        if (session_status() != PHP_SESSION_ACTIVE) {
            session_start();
        }
    }
}