<?php

require_once __DIR__.'/../Conexao.php';
require_once __DIR__.'/../validacao/ValidacaoException.php';

class Usuario 
{
    protected Conexao $conexao;

    public function __construct() {
        $this->conexao = new Conexao();
    }

    public function cadastrar(
        string $usuario, 
        string $senha, 
        string $tipo_usuario, 
        array $id_permissao = []
    ): void {
        // Verifica se usuário já existe
        $this->verificarDuplicidade('usuario', $usuario, null, 'Nome de usuário já está em uso.');
    
        // Insere o novo usuário
        $query = <<<SQL
            INSERT INTO usuarios (usuario, senha, tipousuario) 
            VALUES (:usuario, :senha, :tipo_usuario);
    SQL;
        $stmt = $this->conexao->prepare($query);
        $stmt->execute([
            'usuario' => $usuario,
            'senha' => password_hash($senha, PASSWORD_DEFAULT),
            'tipo_usuario' => $tipo_usuario
        ]);
    }
    
    public function atualizar(
        int $id, 
        string $usuario, 
        string $tipo_usuario, 
        string $senha = null, 
        array $id_permissao = []
    ): void {
        if (empty($id)) {
            throw new \Exception('O código do usuário é obrigatório.');
        }
    
        // Verificar se usuário já existe
        $this->verificarDuplicidade('usuario', $usuario, $id, 'Nome de usuário já está em uso.');
    
        // Atualiza o usuário
        $query = <<<SQL
            UPDATE usuarios 
               SET usuario = :usuario,
                   tipousuario = :tipo_usuario
    SQL;
    
        // Prepara os parâmetros para a atualização
        $params = [
            'id' => $id,
            'usuario' => $usuario,
            'tipo_usuario' => $tipo_usuario
        ];
    
        if (!empty($senha)) {
            $query .= ", senha = :senha";
            $params['senha'] = password_hash($senha, PASSWORD_DEFAULT);
        }
    
        // Finaliza a consulta com a cláusula WHERE
        $query .= " WHERE idusuario = :id";
    
        // Executa a atualização no banco de dados
        $stmt = $this->conexao->prepare($query);
        $stmt->execute($params);
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
    
    public function excluir(int $id): void
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
    }

    public function buscaPorId(int $id): array|false
    {
        $query = <<<SQL

        SELECT * FROM usuarios
        WHERE idusuario = :id
SQL;
        $stmt = $this->conexao->prepare($query);
        $stmt->execute([
            'id' => $id
        ]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function listarTudo(): array
    {
        $query = <<<SQL

        SELECT * FROM usuarios
        ORDER BY Usuario
SQL;
        $stmt = $this->conexao->query($query);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}