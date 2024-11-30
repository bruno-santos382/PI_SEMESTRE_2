const Carrinho = {
    /**
     * Adiciona um produto ao carrinho de compras via AJAX.
     * 
     * @param {Event} event Evento de clique do botão "Adicionar ao carrinho".
     * @returns {Promise<void>}
     */
    adicionarProduto: async function (event) {
        const button = event.currentTarget;

        button.setAttribute('disabled', true);
        const html = button.innerHTML;
        button.textContent = 'Carregando...';
    
        try {
            const dadosForm = new FormData();
            dadosForm.append('id_produto', button.dataset.idProduto);
            const resposta = await fetch('api.php?route=carrinho/adicionar', {
                method: 'POST',
                body: dadosForm
            });
    
            const json = await resposta.json();
            if (json.status === 'ok') {
                bootstrap.Modal.getOrCreateInstance('#modalAdicionarProduto').show();
            } else {
                bootstrap.Modal.getOrCreateInstance('#modalErroAdicionarProduto').show();
            }
    
        } catch (e) {
            console.error(e);
        }
        
        button.removeAttribute('disabled');
        button.innerHTML = html;
    },

    // Função auxiliar para atualizar a quantidade e o preço total
    atualizarQuantidade: function (event, incremento) {
        const button = event.currentTarget;  // Botão que foi clicado
        const cardItem = button.closest('.cart-item');  // Item do carrinho
        const inputQuantidade = cardItem.querySelector('.input-quantidade');  // Campo de quantidade
        const textPrecoTotal = cardItem.querySelector('.preco-total');  // Elemento que exibe o preço total
    
        // Atualiza a quantidade, aplicando o incremento (+1 ou -1)
        const quantidadeAtual = parseInt(inputQuantidade.value);

        if (incremento < 0 && !quantidadeAtual) {
            return;
        }

        const novaQuantidade = Math.max(0,  quantidadeAtual + incremento);
        inputQuantidade.value = novaQuantidade;  // Atualiza o campo de quantidade

        // Calcula o novo preço total com base na quantidade e no preço unitário
        const precoUnitario = parseFloat(button.dataset.precoUnitario);
        const precoTotal = novaQuantidade * precoUnitario;
    
        // Atualiza o preço total do item no carrinho
        textPrecoTotal.textContent = new Intl.NumberFormat('pt-BR', {
            style: 'currency',
            currency: 'BRL'
        }).format(precoTotal);  // Formata o preço total como moeda
    
        // Atualiza o preço total da compra
        const textTotalCompra = document.querySelector('.total-compra');  // Elemento que exibe o preço total da compra
        const totalCompraAtual = parseFloat(textTotalCompra.dataset.valorTotal);
        const totalCompraNovo = totalCompraAtual + (incremento * precoUnitario);
        textTotalCompra.dataset.valorTotal = totalCompraNovo;
    
        // Atualiza o preço total da compra formatado
        textTotalCompra.textContent = new Intl.NumberFormat('pt-BR', {
            style: 'currency',
            currency: 'BRL'
        }).format(totalCompraNovo);  // Formata o preço total da compra
    },    

    // Função para aumentar a quantidade
    aumentarQuantidade: function (event) {
        this.atualizarQuantidade(event, 1);  // Chama a função auxiliar com incremento de +1
    },

    // Função para diminuir a quantidade
    diminuirQuantidade: function (event) {
        this.atualizarQuantidade(event, -1);  // Chama a função auxiliar com incremento de -1
    }
}

document.addEventListener('DOMContentLoaded', () => {
    const btnAdicionarProduto = document.querySelectorAll('.btn-adicionar-produto');
    btnAdicionarProduto.forEach(btn => btn.addEventListener('click', Carrinho.adicionarProduto)); 
});