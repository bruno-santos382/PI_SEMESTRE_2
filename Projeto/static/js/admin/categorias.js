
async function cadastrarCategoria(event) {
    event.preventDefault();

    try {
        const dadosForm = new FormData(this);

        const resposta = await fetch('api.php?route=categoria/cadastrar', {
            method: 'POST',
            body: dadosForm
        });

        const json = await resposta.json();
        if (json.status === 'ok') {
            Alerta.sucesso('#alertaCategoria', 'Cadastrado com sucesso!');
            this.reset();
        } else {
            Alerta.erro('#alertaCategoria', json.mensagem || 'Erro ao realizar cadastro.');
        }
        
    } catch (e) {
        console.error(e)
        Alerta.erro('#alertaCategoria', 'Erro ao realizar cadastro.');
    }
};

async function removerCategoria(btnExcluir, id) {
    if (!confirm('Deseja realmente remover essa categoria?')) {
        return;
    }
    
    btnExcluir.setAttribute('disabled', true);
    const html = btnExcluir.innerHTML;
    btnExcluir.innerHTML = 'Removendo';
    
    try {
        const formData = new FormData();
        formData.append('id', id);
        const resposta = await fetch('api.php?route=categoria/excluir', {
            method: 'POST',
            body: formData
        });

        const json = await resposta.json();
        if (json.status === 'ok') {
            Alerta.sucesso('#alertaCategoria', 'Categoria excluída com sucesso!');
            btnExcluir.closest('tr').remove();
        } else {
            Alerta.erro('#alertaCategoria', json.mensagem || 'Erro ao excluír categoria.');
        }
    } catch (e) {
        console.error(e)
        Alerta.erro('#alertaCategoria', 'Erro ao excluír categoria.');
    }

    btnExcluir.removeAttribute('disabled');
    btnExcluir.innerHTML = html;
}


document.addEventListener('DOMContentLoaded', function() {
    const formCadastro = document.getElementById('formCadastro')
    formCadastro.addEventListener('submit', cadastrarCategoria);
});