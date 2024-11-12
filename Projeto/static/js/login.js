
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

        const dados = await resposta.json(); // Faz o parsing da resposta

        if (dados.status === 'ok') {
            window.location.href = 'cadastro_produto.php'; // Redireciona em caso de sucesso
        } else {
            alert(dados.mensagem || 'Falha no login'); // Caso contrário, exibe mensagem de erro
        }
    } catch (error) {
        console.error('Erro ao efetuar login:', error); // Loga o erro
        alert('Ocorreu um erro inesperado. Tente novamente.'); // Mensagem genérica de erro
    }

    submitButton.disabled = false; // Restaura o botão
    submitButton.textContent = 'Entrar'; // Restaura o texto do botão
}

// Aguarda o carregamento do DOM
document.addEventListener('DOMContentLoaded', (event) => {
    const form = document.getElementById('loginForm');
    form.addEventListener('submit', efetuarLogin); // Adiciona listener para o envio do form
});