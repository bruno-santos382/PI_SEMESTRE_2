<?php

require_once __DIR__.'/ValidacaoException.php';

class Validacao 
{
    /**
     * Obtém e valida dados enviados via POST com base em filtros fornecidos.
     * 
     * @param array $filtros Array de filtros contendo as regras de validação.
     * 
     * @return array Retorna os dados validados.
     * 
     * @throws ValidacaoException Caso algum campo obrigatório esteja vazio ou inválido.
     */
    public function obterDadosPost(array $filtros): array 
    {
        // Aplica os filtros nos dados do POST
        $dados = filter_input_array(INPUT_POST, $filtros, false);

        // Verifica cada filtro para validar campos obrigatórios
        foreach ($filtros as $campo => $filtro) {
            $obrigatorio = $filtro['obrigatorio'] ?? true;
            
            // Lança exceção se o campo obrigatório estiver vazio
            if ($obrigatorio === true && empty($dados[$campo])) {
                throw new ValidacaoException($filtro['erro']);
            }
        }

        return $dados;
    }
}
