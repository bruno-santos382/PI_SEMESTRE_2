<?php

require_once __DIR__.'/../Conexao.php';
require_once __DIR__.'/../validacao/ValidacaoException.php';
require_once __DIR__.'/../autenticacao/Autentica.php';

class Funcionario 
{
	protected Conexao $conexao;

    protected Autentica $autentica;

	public function __construct() {
		$this->conexao = new Conexao();
        $this->autentica = new Autentica();
	}

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