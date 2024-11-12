<?php

require_once __DIR__.'/../validacao/Validacao.php';

class BaseController
{
    protected Validacao $validacao;

    public function __construct()
    {
        $this->validacao = new Validacao();
    }

    protected function realizarAcao(callable $acao, array $filtros=null): array
    {
        $dados = [];

        if (!empty($filtros)) {
            $dados = $this->validacao->obterDadosPost($filtros);
        }
        
        $resultado = $acao(...$dados);
        
        return [
            'status' => 'ok', 
            'mensagem' => 'Ação realizada com sucesso.',
            'dados' => $resultado
        ];
    }
}