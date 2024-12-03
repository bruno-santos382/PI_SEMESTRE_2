
function htmlCategoria(categoria) {
    const paginas = {
        'hortifruti': 'Hortifruti',
        'acougue': 'Açougue',
        'mercearia': 'Mercearia',
        'bebidas': 'Bebidas',
        'padaria': 'Padaria',
        'limpeza': 'Limpeza'
    };

    return `
        <tr data-id-categoria="${categoria.IdCategoria}">
            <td>${categoria.IdCategoria}</td>
            <td>${categoria.Nome}</td>
            <td>${paginas[categoria.Pagina] || '<i class="text-muted">(Nenhuma)</i>'}</td>
            <td>
                <button type="button" class="btn btn-warning btn-sm" title="Editar" 
                        onclick="editarCategoria('${categoria.IdCategoria}', '${categoria.Nome}', '${categoria.Pagina}')">
                    <i class="bi bi-pencil-fill me-1"></i> Editar
                </button>
                <button type="button" class="btn btn-danger btn-sm" onclick="removerCategoria(this, '${categoria.IdCategoria}')" title="Remover">
                    <i class="bi bi-trash-fill me-1"></i> Excluír
                </button>
            </td>
        </tr>
    `;
}

async function cadastrarCategoria(event) {
    event.preventDefault();

    document.querySelector('#alertaEditando').style.maxHeight = '0px';

    try {
        const dadosForm = new FormData(this);

        const resposta = await fetch('api.php?route=categoria/cadastrar', {
            method: 'POST',
            body: dadosForm
        });

        const json = await resposta.json();
        if (json.status === 'ok') {
            this.reset();

            if (dadosForm.get('id') == '') {
                Alerta.sucesso('#alertaCategoria', 'Categoria cadastrada com sucesso!');

                const tr = document.createElement('tr');
                document.querySelector('#dadosTabela').appendChild(tr);
                tr.outerHTML = htmlCategoria(json.dados);
            } else {
                Alerta.sucesso('#alertaCategoria', 'Categoria alterada com sucesso!');
                // Atualizar categoria na tabela
                const tr = document.querySelector(`#dadosTabela [data-id-categoria="${json.dados.IdCategoria}"]`);
                tr.outerHTML = htmlCategoria(json.dados);
            }
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

function editarCategoria(id, nome, pagina) {
    const campoNome = document.querySelector('#nome');
    campoNome.value = nome;
    campoNome.focus();

    document.querySelector('#categoriaSelecionada').textContent = nome;
    document.querySelector('#codigo').value = id;
    document.querySelector('#pagina').value = pagina;

    // Animação no alerta
    document.querySelector('#alertaEditando').style.maxHeight = '60px';
    document.querySelector('#alertaCategoria').firstElementChild.style.maxHeight = '0px';
}


document.addEventListener('DOMContentLoaded', function() {
    const formCadastro = document.getElementById('formCadastro')
    formCadastro.addEventListener('submit', cadastrarCategoria);
});