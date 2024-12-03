<?php

class Conexao extends \PDO {

    /**
     * Construtor da classe Conexao.
     * 
     * Este método inicializa a conexão com o banco de dados utilizando os parâmetros definidos no arquivo de configuração.
     * Ele chama o construtor da classe pai (PDO) para estabelecer a conexão.
     * 
     * @throws \PDOException Se ocorrer um erro ao tentar conectar ao banco de dados.
     */
    public function __construct() {
        // Inclui o arquivo de configuração que contém os detalhes da conexão com o banco de dados
        $config = include __DIR__.'/../includes/config.php';

        // Chama o construtor da classe pai (PDO) passando os parâmetros necessários para estabelecer a conexão com o banco de dados
        parent::__construct(
            $config['db.dsn'],       // DSN (Data Source Name) do banco de dados
            $config['db.username'],  // Nome de usuário do banco de dados
            $config['db.password']   // Senha do banco de dados
        );
    }
}
