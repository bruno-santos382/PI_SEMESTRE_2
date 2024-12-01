<?php

require_once __DIR__.'/../Conexao.php';
require_once __DIR__.'/../validacao/ValidacaoException.php';

// Iniciar sessão caso esteja inativa
if (session_status() != PHP_SESSION_ACTIVE) {
    session_start();
}

/**
 * Class Autentica
 * 
 * Esta classe gerencia a autenticação de usuários, incluindo login, logout e verificação de sessão.
 * Utiliza a classe Conexao para interagir com o banco de dados e a classe ValidacaoException para
 * tratar erros de validação durante o processo de login.
 */
class Autentica 
{
    private Conexao $conexao;

    /**
     * Construtor da classe.
     * 
     * Instancia uma nova conexão com o banco de dados.
     */
    public function __construct()
    {
        $this->conexao = new Conexao();
    }
    
    
    /**
     * Realiza o login do usuário.
     * 
     * O login pode ser feito com o login do usuário, email ou número de telefone.
     * 
     * @throws ValidacaoException Caso o login ou senha seja inválido
     * @param string $login Login do usuário, email ou número de telefone
     * @param string $senha Senha do usuário
     * @return void
     */
    public function login(string $login, string $senha): void
    {
        $query = "SELECT * FROM VW_USUARIOS_ATIVOS WHERE usuario = :login";

        // Caso o formato do login for um email
        if (filter_var($login, FILTER_VALIDATE_EMAIL)) {
            $query = "SELECT * FROM VW_USUARIOS_ATIVOS WHERE email = :login";
        } 
        // Caso o formato do login for um número de telefone
        else {
            $telefone = preg_replace('/[^\d]*/', '', $login); // Apaga todos os caracteres não numéricos
            if (strlen($telefone) >= 10) {
                $query = "SELECT * FROM VW_USUARIOS_ATIVOS WHERE telefone = :login";
                $login = $telefone;
            }
        } 

        $stmt = $this->conexao->prepare($query);
        $stmt->execute(['login' => $login]);
        $row = $stmt->fetch(\PDO::FETCH_OBJ);

        if (!$row || !password_verify($senha, $row->Senha)) {
            throw new ValidacaoException('Usuario ou senha inválidos.');
        }

        session_regenerate_id(true);
        $_SESSION['usuario'] = array(
            'id' => $row->IdUsuario, 
            'nome' => $row->Usuario, 
            'id_pessoa' => $row->IdPessoa
        );
    }

    
    /**
     * Desloga o usuário e remove a sessão.
     */
    public function logout(): void
    {
        unset($_SESSION['usuario']);
    }


    /**
     * Verifica se o usuário está logado e retorna seus dados.
     * 
     * O método verifica se o usuário está logado e retorna seus dados, caso contrário, 
     * retorna false.
     * 
     * @return array|false dados do usuário com as permissões ou false se o usuário não estiver logado
     */
    public function usuarioLogado(): array|false
    {
        // Variável estática para armazenar os dados do usuário
        static $usuario;

        if (empty($usuario)) 
        {
            $usuario = $_SESSION['usuario'] ?? false;

            if (!$usuario) {
                return false;
            }
            
            // Verificar se usuario é válido
            $query = "SELECT EXISTS(SELECT 1 FROM VW_USUARIOS_ATIVOS WHERE idusuario = :idusuario LIMIT 1)";
            $stmt = $this->conexao->prepare($query);
            $stmt->execute(['idusuario' => $usuario['id']]);

            if ($stmt->fetchColumn() != '1') {
                return false;
            }
            
            // Carregar permissões do usuário
            $query = "SELECT Permissao FROM permissao_usuarios WHERE IdUsuario = :id AND DataExclusao IS NULL";
            $stmt = $this->conexao->prepare($query);
            $stmt->execute(['id' => $usuario['id']]);
            $usuario['permissoes'] = $stmt->fetchAll(\PDO::FETCH_COLUMN);
        }

        return $usuario;
    }
}