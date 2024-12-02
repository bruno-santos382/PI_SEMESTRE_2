<?php

require_once __DIR__.'/../Conexao.php';
require_once __DIR__.'/../validacao/ValidacaoException.php';
require_once __DIR__.'/../autenticacao/Autentica.php';

class Usuario 
{
    protected Conexao $conexao;

    protected Autentica $autentica;

    protected array $config;

    public function __construct() {
        $this->conexao = new Conexao();
        $this->autentica = new Autentica();
        $this->config = include __DIR__.'/../../includes/config.php';
    }

    public function cadastrar(
        string $usuario, 
        string $senha, 
        array $permissoes = []
    ): void {
        // Verifica se usuário já existe
        $this->verificarDuplicidade('usuario', $usuario, null, 'Nome de usuário já está em uso.');
    
        // Insere o novo usuário
        $query = "INSERT INTO usuarios (usuario, senha) VALUES (:usuario, :senha)";
        $stmt = $this->conexao->prepare($query);
        $stmt->execute([
            'usuario' => $usuario,
            'senha' => password_hash($senha, PASSWORD_DEFAULT),
        ]);
        $id_usuario = $this->conexao->lastInsertId();
    
        // Insere permissões do usuário
        $this->inserirPermissoes($id_usuario, $permissoes);
    }

    public function atualizar(
        int $id, 
        string $usuario, 
        string $senha = null, 
        array $permissoes = []
    ): array {
        if (empty($id)) {
            throw new \Exception('O código do usuário é obrigatório.');
        }
    
        // Verificar se usuário já existe
        $this->verificarDuplicidade('usuario', $usuario, $id, 'Nome de usuário já está em uso.');
    
        // Prepara a consulta de atualização de usuário
        $query = "UPDATE usuarios SET usuario = :usuario";
    
        // Parâmetros para a atualização
        $params = [
            'id' => $id,
            'usuario' => $usuario,
        ];
    
        if (!empty($senha)) {
            $query .= ", senha = :senha";
            $params['senha'] = password_hash($senha, PASSWORD_DEFAULT);
        }
    
        // Finaliza a consulta com a cláusula WHERE
        $query .= " WHERE idusuario = :id";
    
        // Executa a atualização do usuário
        $stmt = $this->conexao->prepare($query);
        $stmt->execute($params);

        // Insere permissões do usuário
        $this->inserirPermissoes($id, $permissoes);

        // Usuário perdeu acesso ao painel de administração
        if ($this->autentica->usuarioLogado()['id'] == $id && !in_array('acesso_admin', $permissoes)) {
            return ['url_redirecionamento' => $this->config['app.url'].'/index.php'];
        }

        return [];
    }

    private function inserirPermissoes(int $id_usuario, array $permissoes): void {
        // Excluir permissões antigas
        $sql = "DELETE FROM permissao_usuarios WHERE IdUsuario = :IdUsuario";
        $stmt = $this->conexao->prepare($sql);
        $stmt->execute(['IdUsuario' => $id_usuario]);
    
        // Inserir novas permissões
        $sql = "INSERT INTO permissao_usuarios (IdUsuario, Permissao) VALUES (:IdUsuario, :Permissao)";
        $stmt = $this->conexao->prepare($sql);

        foreach ($permissoes as $permissao) {
            $stmt->execute(params: [
                'IdUsuario' => $id_usuario,
                'Permissao' => $permissao,
            ]);
        }
    }
    
    private function verificarDuplicidade(string $campo, string $valor, int $usuario_id = null, string $mensagem_erro): void {
        // Preparar a consulta base
        $query = "SELECT idusuario FROM usuarios WHERE {$campo} = :valor";
    
        // Excluir o usuário atual da verificação se for uma atualização
        if ($usuario_id) {
            $query .= " AND idusuario != :id";
        }
    
        // Preparar e executar a declaração
        $stmt = $this->conexao->prepare("SELECT EXISTS ($query LIMIT 1)");
    
        // Executar com os parâmetros adequados
        if ($usuario_id) {
            $stmt->execute(['valor' => $valor, 'id' => $usuario_id]);
        } else {
            $stmt->execute(['valor' => $valor]);
        }
    
        // Verificar duplicatas
        if ($stmt->fetchColumn() == '1') {
            throw new ValidacaoException($mensagem_erro);
        }
    }
    
    public function excluir(int $id): array
    {        
        if (empty($id)) {
            throw new \Exception('O código do usuário é obrigatório.');
        }

        $query = <<<SQL
            UPDATE usuarios
            SET DataExclusao = CURRENT_TIMESTAMP()
            WHERE idusuario = :id
SQL;
        $stmt = $this->conexao->prepare($query);
        $stmt->execute([
            'id' => $id
        ]);

        $logado = $this->autentica->usuarioLogado();
        if ($logado['id'] == $id) {
            $this->autentica->logout();
            return ['logout' => true];
        }

        return ['logout' => false];
    }

    public function buscaPorId(int $id): array|false
    {
        $query = <<<SQL

        SELECT * FROM usuarios
        WHERE idusuario = :id
SQL;
        $stmt = $this->conexao->prepare($query);
        $stmt->execute(['id' => $id]);
        $usuario =$stmt->fetch(\PDO::FETCH_ASSOC);

        // Selecionar as permissões do usuário
        $query = <<<SQL

        SELECT Permissao FROM permissao_usuarios
        WHERE IdUsuario = :id AND DataExclusao IS NULL
SQL;
        $stmt = $this->conexao->prepare($query);
        $stmt->execute(['id' => $id]);
        $usuario['permissoes'] = $stmt->fetchAll(\PDO::FETCH_COLUMN);

        return $usuario;
    }

    public function listarTudo(): array
    {
        $query = <<<SQL

        SELECT * FROM usuarios
        WHERE DataExclusao IS NULL
        ORDER BY Usuario
SQL;
        $stmt = $this->conexao->query($query);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}