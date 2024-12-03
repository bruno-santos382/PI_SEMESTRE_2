<?php

require_once __DIR__.'/../Conexao.php';
require_once __DIR__.'/../validacao/ValidacaoException.php';
require_once __DIR__.'/../carrinho/Carrinho.php';
require_once __DIR__.'/../usuario/Usuario.php';
require_once __DIR__.'/../cliente/Cliente.php';

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
    
    private Carrinho $carrinho;

    private Usuario $usuario;

    private Cliente $cliente;

    /**
     * Construtor da classe.
     * 
     * Instancia uma nova conexão com o banco de dados.
     */
    public function __construct(
        Conexao $conexao = null, 
        Carrinho $carrinho = null, 
        Usuario $usuario = null, 
        Cliente $cliente = null
    ) {
        $this->conexao = $conexao ?? new Conexao();
        // Passar dependencias por construtor para evitar loop infinito
        // pois Carrinho também instancia um  objeto Autentica
        $this->carrinho = $carrinho ?? new Carrinho($this->conexao, $this);
        $this->usuario = $usuario ?? new Usuario($this->conexao, $this);
        $this->cliente = $cliente ?? new Cliente($this->conexao);
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
    public function login(string $login, string $senha): array
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
            'usuario' => $row->Usuario, 
            'id_pessoa' => $row->IdPessoa,
            'nome' => $row->Nome,
            'email' => $row->Email,
            'telefone' => $row->Telefone,
            'tipo' => $row->Tipo
        );

        // Carregar produtos do carrinho
        $this->carrinho->carregar();

        if (isset($_SESSION['redirecionar_apos_login'])) {
            $url_redirecionamento = $_SESSION['redirecionar_apos_login'];
            unset($_SESSION['redirecionar_apos_login']);
            return ['url_redirecionamento' => $url_redirecionamento];
        }

        return ['url_redirecionamento' => 'index.php'];
    }

    
    /**
     * Desloga o usuário e remove a sessão.
     */
    public function logout(): void
    {
        // Salvar e esvaziar carrinho
        $this->carrinho->salvar();
        $this->carrinho->esvaziar();

        // Efetuar logout
        unset($_SESSION['usuario']);
    }

    public function registrar(
        string $nome, 
        string $email, 
        string $telefone,
        string $senha, 
        string $confirmar_senha
    ): array {
        if ($senha !== $confirmar_senha) {
            throw new ValidacaoException('As senhas devem ser iguais.');
        }

        $login = $email;
        $this->conexao->beginTransaction();
        $usuario = $this->usuario->cadastrar($login, $senha);
        $pessoa = $this->cliente->cadastrar($nome, $email, $telefone, $usuario['IdUsuario']);
        $this->conexao->commit();

        return ['url_redirecionamento' => 'login.php'];
    }

    public function recuperarSenha(string $email): array
    {
        $query = "SELECT * FROM VW_USUARIOS_ATIVOS WHERE email = :email LIMIT 1";
        $stmt = $this->conexao->prepare($query);
        $stmt->execute(['email' => $email]);
        $row = $stmt->fetch(\PDO::FETCH_OBJ);

        if (!$row) {
            throw new ValidacaoException('Email inválido.');
        }
        
        $nova_senha = password_hash('123456', PASSWORD_DEFAULT);
        $query = "UPDATE usuarios SET Senha = :senha WHERE idusuario = :idusuario";
        $stmt = $this->conexao->prepare($query);
        $stmt->execute([
            'senha' => $nova_senha,
            'idusuario' => $row->IdUsuario
        ]);

        return ['nova_senha' => '123456'];
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