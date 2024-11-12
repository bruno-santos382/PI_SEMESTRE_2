<?php

require_once __DIR__.'/ValidacaoException.php';

class Validacao 
{
    public function obterDadosPost(array $filtros): array 
    {
        $dados = filter_input_array(INPUT_POST, $filtros);

        foreach ($filtros as $campo => $filtro) {
            if (!isset($dados[$campo]) || $dados[$campo] === false) {
                throw new ValidacaoException($filtro['erro']);
            }
        }

        return $dados;
    }
}