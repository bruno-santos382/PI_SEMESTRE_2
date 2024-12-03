
// Função assíncrona para efetuar o login
async function efetuarLogin(event) {
    event.preventDefault();
    
    const form = event.target;
    const formData = new FormData(form);
    const submitButton = form.querySelector('[type="submit"]');

    submitButton.disabled = true; // Desabilita o botão
    submitButton.textContent = 'Carregando...'; // Muda o texto do botão

    try {
        const resposta = await fetch('api.php?route=autentica/login', {
            method: 'POST',
            body: formData // Envia os dados do formulário
        });

        if (!resposta.ok) {
            throw new Error('Falha ao enviar o formulário');  // Falha ao processar
        }

        const json = await resposta.json(); // Faz o parsing da resposta

        if (json.status === 'ok') {
            Alerta.sucesso('#alertaLogin', 'Login efetuado com sucesso!');
            submitButton.textContent = 'Entrar';

            setTimeout(function() {
                window.location.href = json.dados.url_redirecionamento; // Redireciona em caso de sucesso depois de um intervalo
            }, 1000)

            return;
        } 
    
        Alerta.erro('#alertaLogin', json.mensagem || 'Falha no login'); // Caso contrário, exibe mensagem de erro
    } catch (error) {
        console.error('#alertaLogin', 'Erro ao efetuar login:', error); // Loga o erro
        Alerta.erro('#alertaLogin', 'Ocorreu um erro inesperado.'); // Mensagem genérica de erro
    }

    submitButton.disabled = false; // Restaura o botão
    submitButton.textContent = 'Entrar'; // Restaura o texto do botão
}

async function recuperarSenha(event) {
    event.preventDefault();

    const form = event.target;
    const formData = new FormData(form);
    const submitButton = form.querySelector('[type="submit"]');

    submitButton.disabled = true; // Desabilita o botão
    submitButton.textContent = 'Carregando...'; // Muda o texto do botão

    try {
        const resposta = await fetch('api.php?route=autentica/recuperar_senha', {
            method: 'POST',
            body: formData // Envia os dados do formulário
        });

        const json = await resposta.json(); // Faz o parsing da resposta

        if (json.status === 'ok') {
            Alerta.sucesso('#alertaRecuperarSenha', 'Senha recuperada com sucesso! Nova senha: ' + json.dados.nova_senha);
        } else {
            Alerta.erro('#alertaRecuperarSenha', json.mensagem || 'Falha ao recuperar senha'); // Caso contrário, exibe mensagem de erro
        }
    } catch (error) {
        console.error('Erro ao efetuar login:', error); // Loga o erro
        Alerta.erro('#alertaRecuperarSenha', 'Ocorreu um erro inesperado.'); // Mensagem genérica de erro
    } finally {
        submitButton.disabled = false; // Restaura o botão
        submitButton.textContent = 'Recuperar Senha'; // Restaura o texto do botão
    }
}

// Aguarda o carregamento do DOM
document.addEventListener('DOMContentLoaded', (event) => {
    const form = document.getElementById('loginForm');
    form.addEventListener('submit', efetuarLogin); // Adiciona listener para o envio do form

    const recuperarSenhaForm = document.getElementById('recuperarSenhaForm');
    recuperarSenhaForm.addEventListener('submit', recuperarSenha);
});