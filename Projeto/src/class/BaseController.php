<?php

require_once __DIR__.'/validacao/Validacao.php';

class BaseController
{
    protected Validacao $validacao;

    /**
     * Construtor da classe BaseController.
     */
    public function __construct()
    {
        $this->validacao = new Validacao();
    }

    /**
     * Realiza uma ação específica após validar os dados do POST (se aplicável).
     * 
     * @param callable $acao Função ou método a ser chamado.
     * @param array|null $filtros Filtros de validação para os dados do POST.
     * 
     * @return mixed Retorna o resultado da execução da ação.
     */
    protected function realizarAcao(callable $acao, array $filtros = null): mixed
    {
        $dados = [];

        // Valida e obtém os dados do POST com base nos filtros fornecidos
        if (!empty($filtros)) {
            $dados = $this->validacao->obterDadosPost($filtros);
        }
        
        // Executa a ação com os dados validados
        return $acao(...$dados);
    }
}
