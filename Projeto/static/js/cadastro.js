
async function realizarCadastro(event) {
    event.preventDefault();

    const form = this;
    const senha = document.querySelector('#senha');
    const confirmarSenha = document.querySelector('#confirmarSenha');

    if (senha.value !== confirmarSenha.value) {
        senha.setCustomValidity('As senhas devem ser iguais');
        confirmarSenha.setCustomValidity('As senhas devem ser iguais');
        form.reportValidity();
        return;
    }

    const button = event.currentTarget.querySelector('button[type="submit"]');
    const buttonHtml = button.innerHTML;
    button.innerHTML = 'Realizando cadastro...';
    button.disabled = true;

    try {
        const dadosForm = new FormData(this);
        const resposta = await fetch('api.php?route=autentica/registrar', {
            method: 'POST',
            body: dadosForm
        });

        const json = await resposta.json();
        if (json.status === 'ok') {
          
            Alerta.sucesso('#alertaCadastro', 'Cadastro realizado com sucesso!');
            this.reset();

            if (json.dados.url_redirecionamento) {
                setTimeout(function() {
                    window.location.replace(json.dados.url_redirecionamento);
                }, 2000);
            }
        } else {
            Alerta.erro('#alertaCadastro', json.mensagem || 'Erro ao realizar cadastro.');
        }
    } catch (e) {
        console.error(e);
        Alerta.erro('#alertaCadastro', 'Erro ao realizar cadastro.');
    } finally {
        button.innerHTML = buttonHtml;
        button.disabled = false;
    }
}


document.addEventListener('DOMContentLoaded', function() {
    document.querySelector('#senha').addEventListener('input', function() {
        this.setCustomValidity('');
    });
    document.querySelector('#confirmarSenha').addEventListener('input', function() {
        this.setCustomValidity('');
    });
    document.querySelector('#formCadastro').addEventListener('submit', realizarCadastro);
});