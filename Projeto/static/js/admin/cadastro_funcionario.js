async function cadastrarFuncionario(event) {
    event.preventDefault();
    const button = event.currentTarget.querySelector('button[type="submit"]');
    button.disabled = true;
    button.textContent = 'Carregando';

    try {
        const dadosForm = new FormData(this);
        const atualizar = Boolean(dadosForm.get('id') !== '');

        const url = atualizar ? 
            'api.php?route=funcionario/atualizar' : 
            'api.php?route=funcionario/cadastrar';

        const resposta = await fetch(url, {
            method: 'POST',
            body: dadosForm
        });

        const json = await resposta.json();
        if (json.status === 'ok') {
            const mensagem = atualizar ? 
            'Funcionário atualizado com sucesso!' : 
            'Funcionário cadastrado com sucesso!';

            Alerta.sucesso('#alertaFuncionario', mensagem);
            if (!atualizar) this.reset();  // Reset form if creating a new employee
        } else {
            Alerta.erro('#alertaFuncionario', json.mensagem || 'Erro ao realizar cadastro.');
        }
    } catch (e) {
        console.error(e);
        Alerta.erro('#alertaFuncionario', 'Erro ao realizar cadastro.');
    } finally {
        button.disabled = false;
        button.textContent = 'Cadastrar';
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const formCadastro = document.getElementById('formCadastro');
    formCadastro.addEventListener('submit', cadastrarFuncionario);
});