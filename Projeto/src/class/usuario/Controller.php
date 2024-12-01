<?php

require_once __DIR__.'/Usuario.php';
require_once __DIR__.'/../BaseController.php';

class UsuarioController extends BaseController
{
    private Usuario $usuario;

    public function __construct() 
    {
        parent::__construct();
        
        $this->usuario = new Usuario();
    }

    public function cadastrarUsuario(): array
    {
        $retorno = $this->realizarAcao([$this->usuario, 'cadastrar'], [
            'usuario' => [
                'filter' => FILTER_DEFAULT, 
                'erro' => 'O nome do usuário é obrigatório e não pode estar em branco.'
            ],
            'email' => [
                'filter' => FILTER_VALIDATE_EMAIL, 
                'erro' => 'O email deve ser um endereço de email válido.',
                'obrigatorio' => false
            ],
            'senha' => [
                'filter' => FILTER_DEFAULT, 
                'erro' => 'A senha do usuário é obrigatória.'
            ],
            'id_permissao' => [
                'filter' => FILTER_DEFAULT, 
                'erro' => 'As permissões do usuário são obrigatórias.',
                'obrigatorio' => false
            ],
            'telefone' => [
                'filter' => FILTER_DEFAULT, 
                'erro' => 'O telefone do usuário é obrigatório.',
                'obrigatorio' => false
            ],
            'tipo_usuario' => [
                'filter' => FILTER_DEFAULT, 
                'erro' => 'O tipo de usuário é obrigatório.',
            ]
        ]);

        return [
            'status' => 'ok', 
            'mensagem' => 'Usuário cadastrado com sucesso!', 
            'dados' => $retorno
        ];
    }

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
            'email' => [
                'filter' => FILTER_VALIDATE_EMAIL, 
                'erro' => 'O email deve ser um endereço de email válido.',
                'obrigatorio' => false
            ],
            'senha' => [
                'filter' => FILTER_DEFAULT, 
                'erro' => 'A senha do usuário é obrigatória.',
                'obrigatorio' => false
            ],
            'id_permissao' => [
                'filter' => FILTER_DEFAULT, 
                'erro' => 'As permissões do usuário são obrigatórias.',
                'obrigatorio' => false
            ],
            'telefone' => [
                'filter' => FILTER_DEFAULT, 
                'erro' => 'O telefone do usuário é obrigatório.',
                'obrigatorio' => false
            ],
            'tipo_usuario' => [
                'filter' => FILTER_DEFAULT, 
                'erro' => 'O tipo de usuário é obrigatório.',
            ]
        ]);

        return [
            'status' => 'ok', 
            'mensagem' => 'Usuário atualizado com sucesso!', 
            'dados' => $retorno
        ];
    }

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
