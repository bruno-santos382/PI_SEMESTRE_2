<?php

require_once __DIR__.'/../Conexao.php';
require_once __DIR__.'/../validacao/ValidacaoException.php';

class Cliente 
{
    protected Conexao $conexao;

    public function __construct() {
        $this->conexao = new Conexao();
    }

    public function cadastrar(
        string $nome, 
        string $email, 
        string $telefone, 
        int $id_usuario = null
    ): void {
        // Verifica se cliente já existe
        $this->verificarDuplicidade('email', $email, null, 'E-mail já está em uso.');
        $this->verificarDuplicidade('telefone', $telefone, null, 'Telefone já está em uso.');
    
        // Insere o novo cliente
        $query = <<<SQL
            INSERT INTO pessoas (nome, email, telefone, idusuario, tipo) 
            VALUES (:nome, :email, :telefone, :idusuario, 'cliente');
SQL;
        $stmt = $this->conexao->prepare($query);
        $stmt->execute([
            'nome' => $nome,
            'email' => $email,
            'telefone' => $telefone,
            'idusuario' => $id_usuario
        ]);
    }
    
    public function atualizar(
        int $id, 
        string $nome, 
        string $email, 
        string $telefone = null, 
        int $id_usuario = null
    ): void {
        if (empty($id)) {
            throw new \Exception('O código do cliente é obrigatório.');
        }
    
        // Verificar se cliente já existe
        $this->verificarDuplicidade('email', $email, $id, 'E-mail já está em uso.');
        $this->verificarDuplicidade('telefone', $telefone, $id, 'Telefone já está em uso.');
    
        // Atualiza o cliente
        $query = <<<SQL
            UPDATE pessoas 
               SET nome = :nome,
                   email = :email,
                   telefone = :telefone,
                   idusuario = :idusuario
SQL;
    
        // Prepara os parâmetros para a atualização
        $params = [
            'id' => $id,
            'nome' => $nome,
            'email' => $email,
            'telefone' => $telefone,
            'idusuario' => $id_usuario
        ];
    
        // Finaliza a consulta com a cláusula WHERE
        $query .= " WHERE IdPessoa = :id";
    
        // Executa a atualização no banco de dados
        $stmt = $this->conexao->prepare($query);
        $stmt->execute($params);
    }
    
    private function verificarDuplicidade(string $campo, string $valor, int $cliente_id = null, string $mensagem_erro): void {
        // Preparar a consulta base
        $query = "SELECT IdPessoa from pessoas WHERE {$campo} = :valor";
    
        // Excluir o cliente atual da verificação se for uma atualização
        if ($cliente_id) {
            $query .= " AND IdPessoa != :id";
        }
    
        // Preparar e executar a declaração
        $stmt = $this->conexao->prepare("SELECT EXISTS ($query LIMIT 1)");
    
        // Executar com os parâmetros adequados
        if ($cliente_id) {
            $stmt->execute(['valor' => $valor, 'id' => $cliente_id]);
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
            throw new \Exception('O código do cliente é obrigatório.');
        }

        $query = <<<SQL
            UPDATE clientes
            SET DataExclusao = CURRENT_TIMESTAMP()
            WHERE IdPessoa = :id
SQL;
        $stmt = $this->conexao->prepare($query);
        $stmt->execute([
            'id' => $id
        ]);
    }

    public function buscaPorId(int $id): array|false
    {
        $query = <<<SQL

        SELECT * from pessoas
        WHERE IdPessoa = :id
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

        SELECT c.*, u.Usuario
        from pessoas c
        JOIN usuarios u ON u.idusuario = c.idusuario AND u.DataExclusao IS NULL
        WHERE c.DataExclusao IS NULL
        ORDER BY c.nome
SQL;
        $stmt = $this->conexao->query($query);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}
