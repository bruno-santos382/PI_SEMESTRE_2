async function cadastrarUsuario(event) {
    event.preventDefault();
    const button = event.currentTarget.querySelector('button[type="submit"]');
    button.disabled = true;
    button.textContent = 'Carregando';

    try {
        const dadosForm = new FormData(this);
        const atualizar = Boolean(dadosForm.get('id') != '');

        const url = atualizar ? 
            'api.php?route=usuario/atualizar' : 
            'api.php?route=usuario/cadastrar';

        const resposta = await fetch(url, {
            method: 'POST',
            body: dadosForm
        });

        const json = await resposta.json();
        if (json.status === 'ok') {
            if (json.dados.url_redirecionamento) {
                window.location.replace(json.dados.url_redirecionamento);
                return;
            }

            const mensagem = atualizar ? 
                'Usuário atualizado com sucesso!' : 
                'Usuário cadastrado com sucesso!';

            Alerta.sucesso('#alertaUsuario', mensagem);
            if (!atualizar) this.reset();
        } else {
            Alerta.erro('#alertaUsuario', json.mensagem || 'Erro ao realizar cadastro.');
        }
    } catch (e) {
        console.error(e);
        Alerta.erro('#alertaUsuario', 'Erro ao realizar cadastro.');
    } finally {
        button.disabled = false;
        button.textContent = 'Cadastrar';
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const formCadastro = document.getElementById('formCadastro');
    formCadastro.addEventListener('submit', cadastrarUsuario);
});
