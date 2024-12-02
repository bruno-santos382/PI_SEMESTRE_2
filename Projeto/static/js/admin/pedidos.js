function htmlPedido(pedido) {
    const dataEntregaSolicitada = new Date(pedido.DataAgendada).toLocaleDateString('pt-BR');
    const dataRetiradaFormatada = pedido.DataRetirada ? new Date(pedido.DataRetirada).toLocaleDateString('pt-BR') : '<b class="text-danger">(Não informada)</b>';

    let acoesPedido = '';
    if (pedido.Status === 'Em Andamento') {
        acoesPedido = `
            <td>
                <button type="button" class="btn btn-sm btn-warning" onclick="editarPedido(event)" 
                    data-id="${pedido.IdPedido}" 
                    data-id-cliente="${pedido.IdPessoa}" 
                    data-data-pedido="${pedido.DataPedido}" 
                    data-data-retirada="${pedido.DataRetirada}"
                    data-valor-total="${pedido.ValorTotal}" 
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
            <td>${pedido.ClienteNome}</td>
            <td>${dataEntregaSolicitada}</td>
            <td>${dataRetiradaFormatada}</td>
            <td>
                R$ ${parseFloat(pedido.ValorTotal).toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}
            </td>
            <td>
                <button type="button" class="btn btn-link" 
                        data-bs-target="#modalDetalhesPedido" 
                        data-bs-toggle="modal"
                        data-id-pedido="${pedido.IdPedido}"
                        data-data-pedido="${pedido.DataPedido}"
                        data-data-agendada="${pedido.DataAgendada}"
                        data-endereco-entrega="${pedido.EnderecoEntrega}"
                        data-metodo-pagamento="${pedido.MetodoPagamento}"
                        data-valor-total="${pedido.ValorTotal}"
                        data-status="${pedido.Status}"
                        onclick="verDetalhesPedido(event)">
                    Ver detalhes
                </button>
            </td>
            <td>
                <button type="button" class="btn btn-link" 
                        data-bs-toggle="modal" 
                        data-bs-target="#modalItensPedido" 
                        data-id="${pedido.IdPedido}" 
                        onclick="verItensPedido(event)">
                    Ver itens
                </button>
            </td>
            
            ${acoesPedido}
        </tr>
    `;
}


async function cadastrarPedido(event) {
    event.preventDefault();

    const button = event.currentTarget.querySelector('button[type="submit"]');
    const buttonHtml = button.innerHTML;
    button.innerHTML = 'Cadastrando...';
    button.disabled = true;

    document.querySelector('#alertaEditando').style.maxHeight = '0px';

    try {
        const dadosForm = new FormData(this);

        const resposta = await fetch('api.php?route=pedido/cadastrar', {
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
                // Atualizar pedido na tabela
                document.querySelector(`tr[data-id-pedido="${json.dados.IdPedido}"]`).outerHTML = htmlPedido(json.dados);
            }
            
          
        } else {
            Alerta.erro('#alertaPedido', json.mensagem || 'Erro ao realizar cadastro.');
        }
        
    } catch (e) {
        console.error(e)
        Alerta.erro('#alertaPedido', 'Erro ao realizar cadastro.');
    } finally {
        button.disabled = false;
        button.innerHTML = buttonHtml;
    }
};

async function cancelarPedido(button, id) {
    if (!confirm('Deseja realmente cancelar esse pedido?')) {
        return;
    }
    
    button.setAttribute('disabled', true);
    
    try {
        const formData = new FormData();
        formData.append('id', id);
        const resposta = await fetch('api.php?route=pedido/cancelar', {
            method: 'POST',
            body: formData
        });

        const json = await resposta.json();
        if (json.status === 'ok') {
            Alerta.sucesso('#alertaPedido', 'Pedido cancelado com sucesso!');
            button.closest('tr').remove();

            // Adicionar na tabela de cancelados
            const tr = document.createElement('tr');
            document.querySelector('#pedidos-cancelados tbody').appendChild(tr);
            tr.outerHTML = htmlPedido(json.dados);
        } else {
            Alerta.erro('#alertaPedido', json.mensagem || 'Erro ao cancelar pedido.');
        }
    } catch (e) {
        console.error(e)
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
        const resposta = await fetch('api.php?route=pedido/finalizar', {
            method: 'POST',
            body: formData
        });

        const json = await resposta.json();
        if (json.status === 'ok') {
            Alerta.sucesso('#alertaPedido', 'Pedido finalizado com sucesso!');
            button.closest('tr').remove();

            // Adicionar na tabela de finalizados
            const tr = document.createElement('tr');
            document.querySelector('#pedidos-finalizados tbody').appendChild(tr);
            tr.outerHTML = htmlPedido(json.dados);
        } else {
            Alerta.erro('#alertaPedido', json.mensagem || 'Erro ao finalizar pedido.');
        }
    } catch (e) {
        console.error(e)
        Alerta.erro('#alertaPedido', 'Erro ao finalizar pedido.');
    }

    button.removeAttribute('disabled');
}

function editarPedido(event) {
    event.preventDefault();

    const dados = event.currentTarget.dataset;

    const campoCliente = document.querySelector('#cliente');
    campoCliente.value = dados.idCliente;
    campoCliente.focus();

    console.log(dados, dados.idCliente, campoCliente.value);

    let [data, hora] = dados.dataPedido.split(' ');
    document.querySelector('#dataPedido').value = data;

    [data, hora] = dados.dataRetirada.split(' ');
    document.querySelector('#dataRetirada').value = data;

    document.querySelector('#codigo').value = dados.id;
    document.querySelector('#valorTotal').value = dados.valorTotal;
    document.querySelector('#pedidoSelecionado').textContent = '#'+dados.id;

    // Animação no alerta
    document.querySelector('#alertaEditando').style.maxHeight = '60px';
    document.querySelector('#alertaPedido').firstElementChild.style.maxHeight = '0px';
}

async function verItensPedido(event) {
    const id = event.currentTarget.dataset.id;
    const carregandoItens = document.querySelector('#carregando-itens-pedido');
    const listaItens = document.querySelector('#lista-itens-pedido');
    const tbody = listaItens.querySelector('tbody')

    tbody.innerHTML = '';
    carregandoItens.classList.remove('d-none');
    listaItens.classList.add('d-none');
    
    try {
        const formData = new FormData();
        formData.append('id', id);
        const resposta = await fetch('api.php?route=pedido/lista_itens', {
            method: 'POST',
            body: formData
        });


        const json = await resposta.json();
        if (json.status !== 'ok') {
            return;
        }

        // Atualizar valor total
        const valorTotalFormatado = `R$ ${Number(json.dados.total).toFixed(2).replace('.', ',')}`;
        document.querySelector('#total-compra-pedido').textContent = valorTotalFormatado;
        
        // Adicionar itens do pedido na tabela
        json.dados.itens.forEach((item) => {
            const tr = document.createElement('tr');
            tr.innerHTML += `
                <td>
                    <div class="d-flex align-items-center">
                        <img src="${item.Imagem || 'static/img/galeria.png'}" alt="${item.Nome}" class="img-fluid" style="width: 60px; height: 60px;">
                        <div class="ms-3">
                            <h5 class="mb-0">${item.Nome}</h5>
                            <small class="text-muted">${item.Marca}</small>
                        </div>
                    </div>
                </td>
                <td class="text-center" style="vertical-align: middle">
                    <span class="quantidade-pedido">${item.Quantidade}</span>
                </td>
                <td class="text-center" style="vertical-align: middle">
                    <strong class=" ${item.PrecoUnitario != item.Preco ? 'text-success' : ''}">R$ ${Number(item.PrecoUnitario).toFixed(2).replace('.', ',')}</strong>
                    ${(item.PrecoUnitario != item.Preco) ? `<small class="text-danger" style="text-decoration: line-through;">R$ ${Number(item.Preco).toFixed(2).replace('.', ',')}</small>` : ''}
                </td>
                <td class="text-center" style="vertical-align: middle">
                    <span class="preco-total">R$ ${Number(item.ValorTotal).toFixed(2).replace('.', ',')}</span>
                </td>
            `;
            tbody.appendChild(tr);
        });
    } catch (e) {
        console.error(e)
    } finally {
        carregandoItens.classList.add('d-none');
        listaItens.classList.remove('d-none');
    }
}

function verDetalhesPedido(event) {
    // Obtém o botão que disparou o evento
    const button = event.currentTarget;
    
    // Acessa os dados via atributos data-*
    const idPedido = button.getAttribute('data-id-pedido');
    const dataPedido = button.getAttribute('data-data-pedido');
    const dataAgendada = button.getAttribute('data-data-agendada');
    const enderecoEntrega = button.getAttribute('data-endereco-entrega');
    const metodoPagamento = button.getAttribute('data-metodo-pagamento');
    const valorTotal = button.getAttribute('data-valor-total');
    const statusPedido = button.getAttribute('data-status');
    
    // Preenche os campos do modal com os dados
    document.getElementById('detalhePedidoId').textContent = idPedido;
    document.getElementById('detalheDataPedido').textContent = new Date(dataPedido).toLocaleDateString();
    document.getElementById('detalheDataAgendada').textContent = dataAgendada ? new Date(dataAgendada).toLocaleDateString() : 'Retirada no mercado';
    document.getElementById('detalheEnderecoEntrega').textContent = enderecoEntrega || 'Não informado';
    document.getElementById('detalheValorTotal').textContent = parseFloat(valorTotal).toFixed(2);
    document.getElementById('detalheStatusPedido').textContent = statusPedido;

    const descricaoMetodoPagamento = {
        'Cartao': 'Pagamento com Cartão',
        'Pix': 'Pagamento via Pix',
        'Dinheiro': 'Pagamento em Dinheiro',
    };
    document.getElementById('detalheMetodoPagamento').textContent = descricaoMetodoPagamento[metodoPagamento] || 'Não informado';
}


document.addEventListener('DOMContentLoaded', function() {
    const formCadastro = document.getElementById('formCadastro')
    formCadastro.addEventListener('submit', cadastrarPedido);
});
