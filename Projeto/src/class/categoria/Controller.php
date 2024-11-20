<?php

require_once __DIR__.'/Categoria.php';
require_once __DIR__.'/../BaseController.php';

class CategoriaController extends BaseController
{
    private Categoria $categoria;

    public function __construct() 
    {
        parent::__construct();
        
        $this->categoria = new Categoria();
    }

    public function cadastrarCategoria(): array
    {
        $retorno = $this->realizarAcao([$this->categoria, 'cadastrar'], [
            'id' => ['filter' => FILTER_DEFAULT, 'obrigatorio' => false],
            'nome' => ['filter' => FILTER_DEFAULT, 'erro' => 'O nome da categoria é obrigatório.'],
            'pagina' => ['filter' => FILTER_DEFAULT, 'obrigatorio' => false]
        ]);

        return [
            'status' => 'ok', 
            'mensagem' => 'Cadastro efetuado com sucesso!', 
            'dados' => $retorno
        ];
    }

    public function excluirCategoria(): array 
    {
        $retorno = $this->realizarAcao([$this->categoria, 'excluir'], [
            'id' => ['filter' => FILTER_DEFAULT, 'erro' => 'O código da categoria é obrigatório.'] ]);

        return [
            'status' => 'ok', 
            'mensagem' => 'Categoria excluída com sucesso!', 
            'dados' => $retorno
        ];
    }
}