<?php

require_once __DIR__.'/ValidacaoException.php';

class Validacao 
{
    public function obterDadosPost(array $filtros): array 
    {
        $dados = filter_input_array(INPUT_POST, $filtros, false);

        foreach ($filtros as $campo => $filtro) {
            $obrigatorio = $filtro['obrigatorio'] ?? true;
            if ($obrigatorio === true && empty($dados[$campo])) {
                throw new ValidacaoException($filtro['erro']);
            }
        }

        return $dados;
    }
}