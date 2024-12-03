<?php

require_once __DIR__.'/../Conexao.php';
require_once __DIR__.'/../validacao/ValidacaoException.php';
require_once __DIR__.'/../autenticacao/Autentica.php';

class Funcionario 
{
	protected Conexao $conexao;

    protected Autentica $autentica;

	/**
	 * Construtor da classe Funcionario.
	 *
	 * Inicializa a conexão com o banco de dados e a autenticação.
	 */
	public function __construct() {
		$this->conexao = new Conexao();
        $this->autentica = new Autentica();
	}


	/**
	 * Cadastra um novo funcionário no sistema.
	 *
	 * Verifica se o funcionário já existe baseado no email e telefone informados.
	 * Caso não exista, insere o funcionário na tabela de pessoas.
	 *
	 * @param string $nome Nome do funcionário.
	 * @param string $email Email do funcionário.
	 * @param string $telefone Telefone do funcionário.
	 * @param int|null $id_usuario ID do usuário associado, opcional.
	 * @throws ValidacaoException Se o email ou telefone já estiver em uso.
	 */
	public function cadastrar(
		string $nome, 
		string $email, 
		string $telefone, 
		int $id_usuario = null
	): void {
		// Verifica se funcionário já existe
		$this->verificarDuplicidade('email', $email, null, 'E-mail já está em uso.');
		$this->verificarDuplicidade('telefone', $telefone, null, 'Telefone já está em uso.');
	
		// Insere o novo funcionário
		$query = <<<SQL
			INSERT INTO pessoas (nome, email, telefone, idusuario, tipo) 
			VALUES (:nome, :email, :telefone, :idusuario, 'funcionario');
SQL;
		$stmt = $this->conexao->prepare($query);
		$stmt->execute([
			'nome' => $nome,
			'email' => $email,
			'telefone' => $telefone,
			'idusuario' => $id_usuario
		]);
	}
	
	/**
	 * Atualiza um funcionário existente no sistema.
	 *
	 * Verifica se o email ou telefone informados já estão em uso por outro funcionário.
	 * Caso estejam, lança uma exceção.
	 * Caso contrário, atualiza o funcionário na tabela de pessoas.
	 *
	 * @param int $id ID do funcionário.
	 * @param string $nome Nome do funcionário.
	 * @param string $email Email do funcionário.
	 * @param string $telefone Telefone do funcionário, opcional.
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
			throw new \Exception('O código do funcionário é obrigatório.');
		}
	
		// Verificar se funcionário já existe
		$this->verificarDuplicidade('email', $email, $id, 'E-mail já está em uso.');
		$this->verificarDuplicidade('telefone', $telefone, $id, 'Telefone já está em uso.');
	
		// Atualiza o funcionário
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
		$query .= " WHERE idpessoa = :id";
	
		// Executa a atualização no banco de dados
		$stmt = $this->conexao->prepare($query);
		$stmt->execute($params);
	}
	
	/**
	 * Verifica se um campo específico possui um valor duplicado na tabela de pessoas.
	 * Se for uma atualização, exclui o funcionário atual da verificação.
	 * 
	 * Lança uma exceção caso encontre um valor duplicado.
	 * 
	 * @param string $campo Nome do campo a ser verificado.
	 * @param string $valor Valor a ser verificado.
	 * @param int|null $funcionario_id ID do funcionário a ser excluído da verificação, se for uma atualização.
	 * @param string $mensagem_erro Mensagem de erro a ser lançada se for encontrado um valor duplicado.
	 * 
	 * @throws ValidacaoException Se o valor do campo já estiver em uso por outro funcionário.
	 */
	private function verificarDuplicidade(string $campo, string $valor, int $funcionario_id = null, string $mensagem_erro): void {
		// Preparar a consulta base
		$query = "SELECT idpessoa from pessoas WHERE {$campo} = :valor";
	
		// Excluir o funcionário atual da verificação se for uma atualização
		if ($funcionario_id) {
			$query .= " AND idpessoa != :id";
		}
	
		// Preparar e executar a declaração
		$stmt = $this->conexao->prepare("SELECT EXISTS ($query LIMIT 1)");
	
		// Executar com os parâmetros adequados
		if ($funcionario_id) {
			$stmt->execute(['valor' => $valor, 'id' => $funcionario_id]);
		} else {
			$stmt->execute(['valor' => $valor]);
		}
	
		// Verificar duplicatas
		if ($stmt->fetchColumn() == '1') {
			throw new ValidacaoException($mensagem_erro);
		}
	}
	
	/**
	 * Exclui um funcionário existente.
	 * 
	 * Marca o funcionário como excluído (soft delete) no banco de dados.
	 * 
	 * Se o funcionário excluído for o usuário logado, efetua logout.
	 * 
	 * @param int $id O ID do funcionário a ser excluído.
	 * 
	 * @throws \Exception Se o ID do funcionário não for fornecido.
	 * 
	 * @return array Um array com a chave 'logout' que indica se o logout foi efetuado.
	 */
	public function excluir(int $id): array
	{        
		if (empty($id)) {
			throw new \Exception('O código do funcionário é obrigatório.');
		}

		// Marcar como excluído (soft delete)
		$query = <<<SQL
			UPDATE pessoas
			SET DataExclusao = CURRENT_TIMESTAMP()
			WHERE idpessoa = :id
SQL;
		
		// Executa a exclusão no banco de dados
		$stmt = $this->conexao->prepare($query);
		$stmt->execute([
			'id' => $id
		]);

        // Se o funcionário excluído for o usuário logado, efetuar logout
        $logado = $this->autentica->usuarioLogado();
        return ['logout' => $logado == false];
	}

	/**
	 * Busca um funcionário pelo ID.
	 * 
	 * @param int $id O ID do funcionário a ser buscado.
	 * 
	 * @return array|false Um array com os dados do funcionário, ou false se não encontrar.
	 */
	public function buscaPorId(int $id): array|false
	{
		// Busca um funcionário pelo ID
		$query = <<<SQL

		SELECT * from pessoas
		WHERE idpessoa = :id AND DataExclusao IS NULL
SQL;
		
		// Prepara e executa a consulta
		$stmt = $this->conexao->prepare($query);
		$stmt->execute([
			'id' => $id
		]);
		
		return $stmt->fetch(\PDO::FETCH_ASSOC);
	}

	/**
	 * Retorna todos os funcionários ativos.
	 *
	 * Os funcionários são retornados em ordem alfabética por nome.
	 * Apenas funcionários não excluídos são incluídos no resultado.
	 *
	 * @return array Um array com os dados dos funcionários.
	 */
	public function listarTudo(): array
	{
		// Lista todos os funcionários que não foram excluídos
		$query = <<<SQL

		SELECT f.*, u.Usuario 
		from pessoas f 
		JOIN usuarios u ON u.idusuario = f.idusuario AND u.DataExclusao IS NULL 
		WHERE f.DataExclusao IS NULL AND f.tipo = 'funcionario'
		ORDER BY f.nome
SQL;

		// Executa a consulta e retorna os resultados
		return ($this->conexao->query($query))->fetchAll(\PDO::FETCH_ASSOC);
	}
}