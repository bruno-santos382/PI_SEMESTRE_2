async function cadastrarCliente(event) {
    event.preventDefault();
    const button = event.currentTarget.querySelector('button[type="submit"]');
    button.disabled = true;
    button.textContent = 'Carregando';

    try {
        const dadosForm = new FormData(this);
        const atualizar = Boolean(dadosForm.get('id') != '');

        const url = atualizar ? 
            'api.php?route=cliente/atualizar' : 
            'api.php?route=cliente/cadastrar';

        const resposta = await fetch(url, {
            method: 'POST',
            body: dadosForm
        });

        const json = await resposta.json();
        if (json.status === 'ok') {
            const mensagem = atualizar ? 
                'Cliente atualizado com sucesso!' : 
                'Cliente cadastrado com sucesso!';

            Alerta.sucesso('#alertaCliente', mensagem);
            if (!atualizar) this.reset();
        } else {
            Alerta.erro('#alertaCliente', json.mensagem || 'Erro ao realizar cadastro.');
        }
    } catch (e) {
        console.error(e);
        Alerta.erro('#alertaCliente', 'Erro ao realizar cadastro.');
    } finally {
        button.disabled = false;
        button.textContent = 'Cadastrar';
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const formCadastro = document.getElementById('formCadastro');
    formCadastro.addEventListener('submit', cadastrarCliente);
});
