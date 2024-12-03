<?php

require_once __DIR__.'/../Conexao.php';
require_once __DIR__.'/../validacao/ValidacaoException.php';

class Cliente 
{
    protected Conexao $conexao;



    /**
     * Construtor da classe Cliente.
     *
     * @param Conexao|null $conexao Instância de conexão com o banco de dados.
     *                             Se não fornecida, uma nova instância será criada.
     */
    public function __construct(Conexao $conexao = null) {
        $this->conexao = $conexao ?? new Conexao();
    }

    /**
     * Cadastra um novo cliente no sistema.
     *
     * Verifica se o cliente já existe baseado no email e telefone informados.
     * Caso não exista, insere o cliente na tabela de pessoas.
     *
     * @param string $nome Nome do cliente.
     * @param string $email Email do cliente.
     * @param string $telefone Telefone do cliente.
     * @param int|null $id_usuario ID do usuário associado, opcional.
     * @return array Dados do cliente cadastrado.
     * @throws ValidacaoException Se o email ou telefone já estiver em uso.
     */
    public function cadastrar(
        string $nome, 
        string $email, 
        string $telefone, 
        int $id_usuario = null
    ): array {
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

        $id_cliente = $this->conexao->lastInsertId();

        return $this->buscaPorId($id_cliente);
    }
    
    /**
     * Atualiza um cliente existente no sistema.
     *
     * Verifica se o email ou telefone informados já estão em uso por outro cliente.
     * Caso estejam, lança uma exceção.
     * Caso contrário, atualiza o cliente na tabela de pessoas.
     *
     * @param int $id ID do cliente.
     * @param string $nome Nome do cliente.
     * @param string $email Email do cliente.
     * @param string $telefone Telefone do cliente, opcional.
     * @param int $id_usuario ID do usuário associado, opcional.
     * @throws ValidacaoException Se o email ou telefone já estiver em uso.
     */
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
    
    /**
     * Verifica se um campo ($campo) tem um valor ($valor) duplicado em outro cliente.
     * Se for uma atualização, o cliente atual é excluído da verificação.
     * Caso encontre um valor duplicado, lança uma exceção com a mensagem informada.
     * 
     * @param string $campo Nome do campo a ser verificado.
     * @param string $valor Valor a ser verificado.
     * @param int $cliente_id ID do cliente, se for uma atualização.
     * @param string $mensagem_erro Mensagem de erro a ser lançada se for encontrado um valor duplicado.
     */
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
    
    /**
     * Marca um cliente como excluído no banco de dados.
     *
     * Atualiza o campo DataExclusao do cliente com a data e hora atuais.
     *
     * @param int $id O ID do cliente a ser excluído.
     * @throws \Exception Se o ID do cliente não for fornecido.
     */
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

    /**
     * Busca um cliente pelo ID.
     *
     * @param int $id ID do cliente.
     * @return array|false Um array com os dados do cliente, ou false se não encontrar.
     */
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

    /**
     * Retorna todos os clientes.
     *
     * Os clientes são retornados em ordem alfabética.
     *
     * @return array Um array com os dados dos clientes.
     */
    public function listarTudo(): array
    {
        $query = <<<SQL

        SELECT c.*, u.Usuario
        from pessoas c
        JOIN usuarios u ON u.idusuario = c.idusuario AND u.DataExclusao IS NULL
        WHERE c.DataExclusao IS NULL AND c.tipo = 'cliente'
        ORDER BY c.nome
SQL;
        $stmt = $this->conexao->query($query);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}
