document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('formCadastro');

    form.addEventListener('submit', async (event) => {
        event.preventDefault();

        try {
            const dadosForm = new FormData(form);

            let resposta = await fetch('controller/?route=produto/cadastrar', {
                method: 'POST',
                body: dadosForm
            });

            const json = await resposta.json();
            
            if (json.sucesso) {
                alert('Cadastrado com sucesso');
            } else {
                alert(json.erro || 'Erro ao realizar cadastro.');
            }
            
        } catch (e) {
            console.error(e)
        }
    });
})
