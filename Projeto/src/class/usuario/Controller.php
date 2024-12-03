<?php

require_once __DIR__.'/Usuario.php';
require_once __DIR__.'/../BaseController.php';

class UsuarioController extends BaseController
{
    private Usuario $usuario;

    /**
     * Construtor da classe UsuarioController.
     * 
     * Inicializa a classe Usuario e chama o construtor da classe pai BaseController.
     */
    public function __construct() 
    {
        parent::__construct();
        
        $this->usuario = new Usuario();
    }

    /**
     * Atualiza um usuário existente.
     * 
     * A atualização de um usuário requer os seguintes dados:
     * - id: O código do usuário é obrigatório.
     * - usuario: O nome do usuário é obrigatório e não pode estar em branco.
     * - senha: A senha do usuário é obrigatória e deve ser um valor alfanumérico válido.
     * - email: O e-mail do usuário é obrigatório e deve ser um endereço de e-mail válido.
     * - telefone: O telefone do usuário é obrigatório e deve ser um número de telefone válido.
     * 
     * @return array Retorna os dados do usuário atualizado.
     */
    public function cadastrarUsuario(): array
    {
        $retorno = $this->realizarAcao([$this->usuario, 'cadastrar'], [
            'usuario' => [
                'filter' => FILTER_DEFAULT, 
                'erro' => 'O nome do usuário é obrigatório e não pode estar em branco.'
            ],
            'senha' => [
                'filter' => FILTER_DEFAULT, 
                'erro' => 'A senha do usuário é obrigatória.'
            ],
            'permissoes' => [
                'flags' => FILTER_REQUIRE_ARRAY, 
                'erro' => 'As permissões do usuário são obrigatórias.',
                'obrigatorio' => false
            ],
        ]);

        return [
            'status' => 'ok', 
            'mensagem' => 'Usuário cadastrado com sucesso!', 
            'dados' => $retorno
        ];
    }

    /**
     * Atualiza um usuário existente.
     * 
     * A atualização de um usuário requer os seguintes dados:
     * - id: O código do usuário é obrigatório.
     * - usuario: O nome do usuário é obrigatório e não pode estar em branco.
     * - senha: A senha do usuário é obrigatória e deve ser um valor alfanumérico válido.
     * - email: O e-mail do usuário é obrigatório e deve ser um endereço de e-mail válido.
     * - telefone: O telefone do usuário é obrigatório e deve ser um número de telefone válido.
     * - permissoes: As permissões do usuário são obrigatórias e devem ser um array de valores numéricos válidos.
     * 
     * @return array Retorna os dados do usuário atualizado.
     */
    public function atualizarUsuario(): array 
    {
        $retorno = $this->realizarAcao([$this->usuario, 'atualizar'], [
            'id' => [
                'filter' => FILTER_DEFAULT, 
                'erro' => 'O código do usuário é obrigatório.'
            ],
            'usuario' => [
                'filter' => FILTER_DEFAULT, 
                'erro' => 'O nome do usuário é obrigatório e não pode estar em branco.'
            ],
            'senha' => [
                'filter' => FILTER_DEFAULT, 
                'erro' => 'A senha do usuário é obrigatória.',
                'obrigatorio' => false
            ],
            'permissoes' => [
                'flags' => FILTER_REQUIRE_ARRAY, 
                'erro' => 'As permissões do usuário são obrigatórias.',
                'obrigatorio' => false
            ],
        ]);

        return [
            'status' => 'ok', 
            'mensagem' => 'Usuário atualizado com sucesso!', 
            'dados' => $retorno
        ];
    }

    /**
     * Exclui um usuário existente.
     * 
     * A exclusão de um usuário requer o código do usuário, que é obrigatório.
     * 
     * @return array Retorna os dados do usuário excluído.
     */
    public function excluirUsuario(): array 
    {
        $retorno = $this->realizarAcao([$this->usuario, 'excluir'], [
            'id' => [
                'filter' => FILTER_DEFAULT, 
                'erro' => 'O código do usuário é obrigatório.'
            ]
        ]);

        return [
            'status' => 'ok', 
            'mensagem' => 'Usuário excluído com sucesso!', 
            'dados' => $retorno
        ];
    }
}
