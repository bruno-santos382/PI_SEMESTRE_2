document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('formCadastro');

    form.addEventListener('submit', async (event) => {
        event.preventDefault();

        try {
            const dadosForm = new FormData(form);

            const resposta = await fetch('api.php?route=produto/cadastrar', {
                method: 'POST',
                body: dadosForm
            });

            const json = await resposta.json();
            if (json.status === 'ok') {
                Alerta.sucesso('Cadastrado com sucesso!');
                form.reset();
            } else {
                Alerta.erro(json.erro || 'Erro ao realizar cadastro.');
            }
            
        } catch (e) {
            console.error(e)
            Alerta.erro('Erro ao realizar cadastro.');
        }
    });
})
