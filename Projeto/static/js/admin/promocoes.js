function htmlPromocao(promocao) {
    const dataInicioFormatada = new Date(promocao.DataInicio).toLocaleDateString('pt-BR');
    const dataFimFormatada = new Date(promocao.DataFim).toLocaleDateString('pt-BR');

    return `
        <tr data-id-promocao="${promocao.IdPromocao}">
            <td>${promocao.Nome}</td>
            <td>
                <img src="${promocao.Imagem || 'static/img/galeria.png'}" 
                     alt="${promocao.Nome}" 
                     class="img-fluid" style="max-width: 50px; height: auto;">
            </td>
            <td>${dataInicioFormatada}</td>
            <td>${dataFimFormatada}</td>
            <td>${promocao.Desconto}%</td>
            <td class="text-danger fw-bold">
                R$ ${parseFloat(promocao.PrecoAntigo).toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}
            </td>
            <td class="text-success fw-bold">
                R$ ${parseFloat(promocao.PrecoComDesconto).toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}
            </td>
            <td>
                <a href="#" 
                   class="btn btn-sm btn-warning" 
                   onclick="editarPromocao(event)" 
                   data-id="${promocao.IdPromocao}" 
                   data-id-produto="${promocao.IdProduto}" 
                   data-data-inicio="${promocao.DataInicio}" 
                   data-data-fim="${promocao.DataFim}"
                   data-desconto="${promocao.Desconto}">
                   <i class="bi bi-pencil me-1"></i> Editar
                </a>
                <a href="#" style="width: 100px;" class="btn btn-sm btn-danger">
                    <i class="bi bi-trash me-1"></i> Excluir
                </a>
            </td>
        </tr>
    `;
}

async function cadastrarPromocao(event) {
    event.preventDefault();

    const button = event.currentTarget.querySelector('button[type="submit"]');
    const buttonHtml = button.innerHTML;
    button.innerHTML = 'Cadastrando...';
    button.disabled = true;

    document.querySelector('#alertaEditando').style.maxHeight = '0px';

    try {
        const dadosForm = new FormData(this);

        const resposta = await fetch('api.php?route=promocao/cadastrar', {
            method: 'POST',
            body: dadosForm
        });

        const json = await resposta.json();
        if (json.status === 'ok') {
            this.reset();

            if (dadosForm.get('id') == '') {
                Alerta.sucesso('#alertaPromocao', 'Promoção cadastrada com sucesso!');
            } else {
                Alerta.sucesso('#alertaPromocao', 'Promoção alterada com sucesso!');
                // Atualizar promocao na tabela
                document.querySelector(`tr[data-id-promocao="${json.dados.IdPromocao}"]`).remove();
            }
            
            const tr = document.createElement('tr');
            document.querySelector(
                json.dados.Status === 'Expirado' 
                    ? '#promocoes-expiradas tbody' 
                    : '#promocoes-ativas tbody'
            ).appendChild(tr);                
            tr.outerHTML = htmlPromocao(json.dados);
        } else {
            Alerta.erro('#alertaPromocao', json.mensagem || 'Erro ao realizar cadastro.');
        }
        
    } catch (e) {
        console.error(e)
        Alerta.erro('#alertaPromocao', 'Erro ao realizar cadastro.');
    } finally {
        button.disabled = false;
        button.innerHTML = buttonHtml;
    }
};

async function removerPromocao(btnExcluir, id) {
    if (!confirm('Deseja realmente remover essa promoção?')) {
        return;
    }
    
    btnExcluir.setAttribute('disabled', true);
    
    try {
        const formData = new FormData();
        formData.append('id', id);
        const resposta = await fetch('api.php?route=promocao/excluir', {
            method: 'POST',
            body: formData
        });

        const json = await resposta.json();
        if (json.status === 'ok') {
            Alerta.sucesso('#alertaPromocao', 'Promoção excluída com sucesso!');
            btnExcluir.closest('tr').remove();
        } else {
            Alerta.erro('#alertaPromocao', json.mensagem || 'Erro ao excluír promoção.');
        }
    } catch (e) {
        console.error(e)
        Alerta.erro('#alertaPromocao', 'Erro ao excluír promoção.');
    }

    btnExcluir.removeAttribute('disabled');
}

function editarPromocao(event) {
    event.preventDefault();

    const dados = event.currentTarget.dataset;
    const id = dados.id;
    const id_produto = dados.idProduto;
    const data_inicio = dados.dataInicio;
    const data_fim = dados.dataFim;
    const desconto = dados.desconto;

    const campoProduto = document.querySelector('#produto');
    campoProduto.value = id_produto;
    campoProduto.focus();

    document.querySelector('#codigo').value = id;
    document.querySelector('#dataInicio').value = data_inicio;
    document.querySelector('#dataFim').value = data_fim;
    document.querySelector('#desconto').value = desconto;
    document.querySelector('#produtoSelecionado').textContent = campoProduto.options[campoProduto.selectedIndex].text;

    // Animação no alerta
    document.querySelector('#alertaEditando').style.maxHeight = '60px';
    document.querySelector('#alertaPromocao').firstElementChild.style.maxHeight = '0px';
}



document.addEventListener('DOMContentLoaded', function() {
    const formCadastro = document.getElementById('formCadastro')
    formCadastro.addEventListener('submit', cadastrarPromocao);
});