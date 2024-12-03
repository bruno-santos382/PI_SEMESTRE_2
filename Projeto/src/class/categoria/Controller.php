<?php

require_once __DIR__.'/Categoria.php';
require_once __DIR__.'/../BaseController.php';

class CategoriaController extends BaseController
{
    private Categoria $categoria;

    /**
     * Construtor da classe.
     *
     * Chama o construtor da classe pai e instancia um objeto Categoria.
     */
    public function __construct() 
    {
        parent::__construct();
        
        $this->categoria = new Categoria();
    }


    /**
     * Cadastra ou atualiza uma categoria.
     *
     * Se um ID for fornecido, a categoria existente será atualizada.
     * Caso contrário, uma nova categoria será inserida.
     *
     * @return array Os dados da categoria cadastrada ou atualizada.
     */
    public function cadastrarCategoria(): array
    {
        $retorno = $this->realizarAcao([$this->categoria, 'cadastrar'], [
            'id' => ['filter' => FILTER_VALIDATE_INT, 'obrigatorio' => false],
            'nome' => ['filter' => FILTER_DEFAULT, 'erro' => 'O nome da categoria é obrigatório.'],
            'pagina' => ['filter' => FILTER_DEFAULT, 'obrigatorio' => false]
        ]);

        return [
            'status' => 'ok', 
            'mensagem' => 'Cadastro efetuado com sucesso!', 
            'dados' => $retorno
        ];
    }

    /**
     * Marca uma categoria de produto como excluída no banco de dados.
     * 
     * Atualiza o campo DataExclusao da categoria com a data e hora atuais.
     * 
     * @param int $id O ID da categoria a ser excluída.
     * @return array Um array com as chaves 'status', 'mensagem' e 'dados'. 
     *               'status' é uma string que pode ter o valor 'ok' ou 'erro'.
     *               'mensagem' é uma string que pode ser exibida ao usuário.
     *               'dados' é um array com a chave 'id' que é o ID da categoria excluída.
     */
    public function excluirCategoria(): array 
    {
        $retorno = $this->realizarAcao([$this->categoria, 'excluir'], [
            'id' => ['filter' => FILTER_VALIDATE_INT, 'erro' => 'O código da categoria é obrigatório.'] 
        ]);

        return [
            'status' => 'ok', 
            'mensagem' => 'Categoria excluída com sucesso!', 
            'dados' => $retorno
        ];
    }
}