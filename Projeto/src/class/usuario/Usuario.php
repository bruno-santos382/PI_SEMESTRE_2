<?php

require_once __DIR__.'/../Conexao.php';
require_once __DIR__.'/../validacao/ValidacaoException.php';
require_once __DIR__.'/../autenticacao/Autentica.php';

class Usuario 
{
    protected Conexao $conexao;

    protected Autentica $autentica;

    protected array $config;

   
    /**
     * Constroi um novo objeto Usuario.
     * 
     * @param Conexao $conexao inst ncia da classe Conexao
     * @param Autentica $autentica inst ncia da classe Autentica
     */
    public function __construct(Conexao $conexao=null, Autentica $autentica=null) {
        $this->conexao = $conexao ?? new Conexao();
        $this->autentica = $autentica ?? new Autentica($this->conexao, null, $this);
        $this->config = include __DIR__.'/../../includes/config.php';
    }

    /**
     * Cadastra um novo usuário no banco de dados.
     * 
     * O nome de usuário e a senha são obrigatórios. Caso o nome de usuário 
     * esteja em uso, uma exce o   lançada.
     * 
     * @param string $usuario nome do usu rio
     * @param string $senha senha do usu rio
     * @param array $permissoes permiss es do usu rio. Caso seja um array vazio,
     *                          n o ser o adicionado nenhuma permiss o.
     * @return array o usu rio cadastrado
     * @throws ValidacaoException caso o nome de usu rio j  esteja em uso
     */
    public function cadastrar(
        string $usuario, 
        string $senha, 
        array $permissoes = []
    ): array {
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
        
        return $this->buscaPorId($id_usuario);
    }

    /**
     * Atualiza um usuário existente no sistema.
     * 
     * O código do usuário e o nome de usuário são obrigatórios. Caso o nome de
     * usuário esteja em uso por outro usuário, uma exce o   lançada.
     * 
     * @param int $id C digo do usu rio.
     * @param string $usuario Novo nome do usu rio.
     * @param string $senha Nova senha do usu rio, opcional.
     * @param array $permissoes Permiss es do usu rio. Caso seja um array vazio,
     *                          n o ser o adicionado nenhuma permiss o.
     * @return array Contendo a URL de redirecionamento após a atualiza o, se o usu rio
     *               perdeu acesso ao painel de administra o.
     * @throws ValidacaoException Caso o nome de usu rio j  esteja em uso
     */
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

    /**
     * Insere as permissões do usuário no banco de dados.
     * 
     * Remove todas as permissões antigas e insere as novas permissões.
     * 
     * @param int $id_usuario C digo do usu rio.
     * @param array $permissoes Permiss es do usu rio.
     */
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
    
    /**
     * Verifica se um determinado valor em um campo do banco de dados 
     * (usuários) já existe.
     * 
     * @param string $campo Campo do banco de dados que deve ser verificado.
     * @param string $valor Valor que deve ser verificado.
     * @param int $usuario_id [optional] C digo do usu rio que est  sendo atualizado.
     *                        Se for passado, o usu rio atual ser  exclu do da verific a o.
     * @param string $mensagem_erro [optional] Mensagem de erro que ser  mostrada caso o valor j  exista.
     *                              Se n o for passado, a mensagem padr o ser  "Valor j  existe".
     * @throws ValidacaoException Caso o valor j  exista.
     */
    private function verificarDuplicidade(string $campo, string $valor, int $usuario_id = null, string $mensagem_erro): void {
        // Preparar a consulta base
        $query = "SELECT idusuario FROM usuarios WHERE {$campo} = :valor AND dataexclusao IS NULL";
    
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
    
    /**
     * Exclui um usuário existente.
     * 
     * Marca o usuário como excluído (soft delete) no banco de dados. 
     * Se o usuário excluído for o usuário logado, efetua logout.
     * 
     * @param int $id O ID do usuário a ser excluído.
     * 
     * @throws \Exception Se o ID do usuário não for fornecido.
     * 
     * @return array Um array com a chave 'logout' que indica se o logout foi efetuado.
     */
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

    /**
     * Busca um usuário pelo seu ID.
     * 
     * @param int $id O ID do usuário a ser buscado.
     * 
     * @return array|false Um array com os dados do usuário, incluindo suas permissões, ou false se não encontrado.
     */
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

    /**
     * Retorna todos os usu rios existentes.
     * 
     * Os usu rios s o retornados em ordem alfab tica.
     * 
     * @return array Um array com os dados dos usu rios.
     */
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