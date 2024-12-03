function htmlPedido(pedido) {
    const dataPedido = new Date(pedido.DataPedido).toLocaleDateString('pt-BR');

    let acoesPedido = '';
    if (pedido.Status === 'Pendente') {
        acoesPedido = `
            <td>
                <button type="button" class="btn btn-sm btn-warning" onclick="editarPedido(event)" 
                    data-id="${pedido.IdPedido}" 
                    data-nome="${pedido.Nome}" 
                    data-telefone="${pedido.Telefone}" 
                    data-quantidade="${pedido.Quantidade}"
                    data-preco-unitario="${pedido.PrecoUnitario}" 
                    data-observacoes="${pedido.Observacoes}"
                    data-status="${pedido.Status}">
                    <i class="bi bi-pencil-fill me-1"></i> Editar
                </button>
                <button type="button" class="btn btn-success btn-sm" onclick="finalizarPedido(this, '${pedido.IdPedido}')">
                    <i class="bi bi-check-circle-fill me-1"></i> Finalizar
                </button>
                <button type="button" class="btn btn-sm btn-danger" onclick="cancelarPedido(this, '${pedido.IdPedido}')">
                    <i class="bi bi-trash-fill me-1"></i> Cancelar
                </button>
            </td>
        `;
    }

    return `
        <tr data-id-pedido="${pedido.IdPedido}">
            <td>${pedido.IdPedido}</td>
            <td>${pedido.Nome}</td>
            <td>${pedido.Telefone}</td>
            <td>${pedido.Quantidade}</td>
            <td>R$ ${parseFloat(pedido.Total).toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}</td>
            <td>${dataPedido}</td>
            <td>${pedido.Observacoes}</td>
            ${acoesPedido}
        </tr>
    `;
}

async function cadastrarPedido(event) {    
    event.preventDefault();

    document.querySelector('#alertaEditando').style.maxHeight = '0px';

    const button = event.currentTarget.querySelector('button[type="submit"]');
    const buttonHtml = button.innerHTML;
    button.innerHTML = 'Cadastrando...';
    button.disabled = true;

    try {
        const dadosForm = new FormData(this);

        const resposta = await fetch('api.php?route=frango_assado/novo_pedido', {
            method: 'POST',
            body: dadosForm
        });

        const json = await resposta.json();
        if (json.status === 'ok') {
            this.reset();

            if (dadosForm.get('id') == '') {
                Alerta.sucesso('#alertaPedido', 'Pedido cadastrado com sucesso!');
                const tr = document.createElement('tr');
                document.querySelector('#pedidos-ativos tbody').appendChild(tr);                
                tr.outerHTML = htmlPedido(json.dados);
            } else {
                Alerta.sucesso('#alertaPedido', 'Pedido alterado com sucesso!');
                document.querySelector(`tr[data-id-pedido="${json.dados.IdPedido}"]`).outerHTML = htmlPedido(json.dados);
            }
        } else {
            Alerta.erro('#alertaPedido', json.mensagem || 'Erro ao realizar cadastro.');
        }
    } catch (e) {
        console.error(e);
        Alerta.erro('#alertaPedido', 'Erro ao realizar cadastro.');
    } finally {
        button.disabled = false;
        button.innerHTML = buttonHtml;
    }
}

async function cancelarPedido(button, id) {
    if (!confirm('Deseja realmente cancelar esse pedido?')) {
        return;
    }
    
    button.setAttribute('disabled', true);
    
    try {
        const formData = new FormData();
        formData.append('id', id);
        const resposta = await fetch('api.php?route=frango_assado/cancelar_pedido', {
            method: 'POST',
            body: formData
        });

        const json = await resposta.json();
        if (json.status === 'ok') {
            Alerta.sucesso('#alertaPedido', 'Pedido cancelado com sucesso!');
            button.closest('tr').remove();

            const tr = document.createElement('tr');
            document.querySelector('#pedidos-cancelados tbody').appendChild(tr);
            tr.outerHTML = htmlPedido(json.dados);
        } else {
            Alerta.erro('#alertaPedido', json.mensagem || 'Erro ao cancelar pedido.');
        }
    } catch (e) {
        console.error(e);
        Alerta.erro('#alertaPedido', 'Erro ao cancelar pedido.');
    }

    button.removeAttribute('disabled');
}

async function finalizarPedido(button, id) {
    if (!confirm('Deseja realmente finalizar esse pedido?')) {
        return;
    }
    
    button.setAttribute('disabled', true);
    
    try {
        const formData = new FormData();
        formData.append('id', id);
        const resposta = await fetch('api.php?route=frango_assado/finalizar_pedido', {
            method: 'POST',
            body: formData
        });

        const json = await resposta.json();
        if (json.status === 'ok') {
            Alerta.sucesso('#alertaPedido', 'Pedido finalizado com sucesso!');
            button.closest('tr').remove();

            const tr = document.createElement('tr');
            document.querySelector('#pedidos-finalizados tbody').appendChild(tr);
            tr.outerHTML = htmlPedido(json.dados);
        } else {
            Alerta.erro('#alertaPedido', json.mensagem || 'Erro ao finalizar pedido.');
        }
    } catch (e) {
        console.error(e);
        Alerta.erro('#alertaPedido', 'Erro ao finalizar pedido.');
    }

    button.removeAttribute('disabled');
}

function editarPedido(event) {
    event.preventDefault();

    const dados = event.currentTarget.dataset;

    const inputCliente = document.querySelector('#cliente');
    inputCliente.value = dados.nome;
    inputCliente.focus();

    const campoTelefone = document.querySelector('#telefone');
    campoTelefone.value = dados.telefone;

    let [dataPedido, horaPedido] = dados.dataPedido.split(' ');
    document.querySelector('#dataPedido').value = dataPedido;

    document.querySelector('#quantidade').value = dados.quantidade;
    document.querySelector('#observacoes').value = dados.observacoes;
    document.querySelector('#total').value = dados.total;
    document.querySelector('#pedidoSelecionado').textContent = '#' + dados.id;
    document.querySelector('#codigo').value = dados.id;

    // Animação no alerta
    document.querySelector('#alertaEditando').style.maxHeight = '60px';
    document.querySelector('#alertaPedido').firstElementChild.style.maxHeight = '0px';
}


document.addEventListener('DOMContentLoaded', function() {
    const formCadastro = document.getElementById('formCadastro');
    formCadastro.addEventListener('submit', cadastrarPedido);
});
