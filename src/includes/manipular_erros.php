<?php

// Define o caminho para o arquivo de log
define('ARQUIVO_LOGS', __DIR__ . '/../logs/error.txt');

// Configurações de exibição e log de erros
ini_set('display_errors', false);  // Não exibir erros na tela
ini_set('log_errors', true);       // Ativar o registro de erros no log
ini_set('error_log', ARQUIVO_LOGS);    // Definir o arquivo de log

// Função personalizada para tratar erros
function tratarErro($errno, $errstr, $errfile, $errline) {
    // Preparar a mensagem de erro, incluindo o número do erro, a descrição, o arquivo e a linha
    $mensagem = '[' . date('Y-m-d H:i:s') . "] Erro [$errno]: $errstr em $errfile na linha $errline\n";
    
    // Registrar o erro no arquivo de log
    error_log($mensagem, 3, ARQUIVO_LOGS);
    
    // Opcionalmente, retornar `true` para evitar que o erro padrão seja mostrado na tela
    return true;
}

// Função personalizada para tratar exceções não capturadas
function tratarException($exception) {
    // Preparar a mensagem de exceção, incluindo o arquivo e a linha da exceção
    $mensagem = '[' . date('Y-m-d H:i:s') . '] Exceção não capturada: ' . $exception->getMessage() . "\n";
    $mensagem .= 'Arquivo: ' . $exception->getFile() . ' na linha ' . $exception->getLine() . "\n";
    
    // Adicionar a pilha de chamadas da exceção para mais detalhes (se necessário)
    $mensagem .= "Pilha de chamadas: " . $exception->getTraceAsString() . "\n";

    // Registrar a exceção no arquivo de log
    error_log($mensagem, 3, ARQUIVO_LOGS);
    
    // Opcionalmente, retornar `true` para evitar a exibição do erro padrão
    return true;
}


// Registrar o tratador de erros e exceções personalizados
set_error_handler('tratarErro');
set_exception_handler('tratarException');

?>
