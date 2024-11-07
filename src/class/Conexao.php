<?php

class Conexao extends \PDO {
    public function __construct() {
        $config = include '../includes/config.php';

        parent::__construct(
            $config['db.dsn'], 
            $config['db.username'], 
            $config['db.password']
        );
    }
}
