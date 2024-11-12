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

    public function login(string $usuario, string $senha): void
    {
        $query = <<<SQL
        SELECT id, senha
        FROM usuarios
        WHERE usuario = :usuario
SQL;
        $stmt = $this->conexao->prepare($query);
        $stmt->execute(['usuario' => $usuario]);
        $row = $stmt->fetch(Conexao::FETCH_OBJ);

        if (!$row || !password_verify($senha, $row->senha)) {
            throw new ValidacaoException('Usuario ou senha inválidos.');
        }

        $this->verificaSessao();
        session_regenerate_id(true);
        $_SESSION['usuario'] = array('id' => $row->id);
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