const Carrinho = {
    /**
     * Formata valores para o formato monetário (R$).
     * 
     * @param {number} valor O valor a ser formatado.
     * @returns {string} O valor formatado em moeda brasileira.
     */
    formatarMoeda: function (valor) {
        return new Intl.NumberFormat('pt-BR', {
            style: 'currency',
            currency: 'BRL'
        }).format(valor);
    },

    /**
     * Função auxiliar para processar ações do carrinho via AJAX (adicionar ou remover produto).
     * 
     * @param {string} route A rota da API para adicionar ou remover o produto.
     * @param {Event} event Evento de clique no botão.
     * @param {Function} onSuccess Função de sucesso.
     * @param {Function} onError Função de erro.
     */
    processarCarrinho: async function (route, event, onSucesso, onErro) {
        event.preventDefault();

        const button = event.currentTarget;
        button.setAttribute('disabled', true);

        try {
            const dadosForm = new FormData();
            dadosForm.append('id_produto', button.dataset.idProduto);
            const resposta = await fetch(`api.php?route=${route}`, {
                method: 'POST',
                body: dadosForm
            });

            const json = await resposta.json();
            if (json.status === 'ok') {
                onSucesso(json.dados);
            } else {
                onErro(json.mensagem);
            }
        } catch (e) {
            console.error(e);
            onErro('Erro ao processar o carrinho.');
        } finally {
            button.removeAttribute('disabled');
        }
    },

    /**
     * Esvazia o carrinho de compras via AJAX.
     * 
     * @returns {Promise<void>}
     */
    esvaziarCarrinho: function (event) {
        Carrinho.processarCarrinho(
            'carrinho/esvaziar',
            event,
            () => {
                document.querySelector('.cart-empty').classList.remove('d-none');
                document.querySelector('#alertaCarrinho').firstElementChild.style.maxHeight = '0px';
                document.querySelector('.lista-produtos').classList.add('d-none');
            },
            (mensagem) => {
                // Exibe mensagem de erro caso a API retorne falha
                Alerta.erro('#alertaCarrinho', mensagem || 'Erro ao esvaziar o carrinho.');
            }
        );
    },


    /**
     * Adiciona um produto ao carrinho de compras via AJAX.
     * 
     * @param {Event} event Evento de clique do botão "Adicionar ao carrinho".
     * @returns {Promise<void>}
     */
    adicionarProduto: function (event) {
        Carrinho.processarCarrinho(
            'carrinho/adicionar',
            event,
            () => bootstrap.Modal.getOrCreateInstance('#modalAdicionarProduto').show(),
            (mensagem) => bootstrap.Modal.getOrCreateInstance('#modalErroAdicionarProduto').show()
        );
    },

    /**
     * Remove um produto do carrinho via AJAX.
     * 
     * @param {Event} event Evento de clique do botão "Remover do carrinho".
     */
    removerProduto: function (event) {
        Carrinho.processarCarrinho(
            'carrinho/remover',
            event,
            (dados) => {
                console.log(event, dados)
                Alerta.sucesso('#alertaCarrinho', 'Produto removido com sucesso!');

                const button = event.currentTarget || event.target;
                console.log(button, button.closest('.cart-item'));
                const cardItem = button.closest('.cart-item');
                const inputQuantidade = cardItem.querySelector('.input-quantidade');
                const quantidadeAtual = parseInt(inputQuantidade.value);
                const precoUnitario = parseFloat(button.dataset.precoUnitario);
                const totalCompraAtual = parseFloat(document.querySelector('.total-compra').dataset.valorTotal);
                const totalCompraNovo = totalCompraAtual - (quantidadeAtual * precoUnitario);
                
                // Atualiza o preço total da compra
                document.querySelector('.total-compra').dataset.valorTotal = totalCompraNovo;
                document.querySelector('.total-compra').textContent = this.formatarMoeda(totalCompraNovo);
                
                // Remove o item do carrinho
                cardItem.remove();

                // Verifica se o carrinho ficou vazio
                if (document.querySelectorAll('.cart-item').length === 0) {
                    document.querySelector('.cart-empty').classList.remove('d-none');
                    document.querySelector('#alertaCarrinho').firstElementChild.style.maxHeight = '0px';
                    document.querySelector('.lista-produtos').classList.add('d-none');
                }
            },
            (mensagem) => Alerta.erro('#alertaCarrinho', mensagem || 'Erro ao remover o item do carrinho.')
        );
    },

    /**
     * Atualiza a quantidade de um produto no carrinho e ajusta o preço total.
     * 
     * @param {Event} event Evento de clique do botão de aumentar/diminuir a quantidade.
     * @param {number} incremento O valor do incremento (+1 ou -1).
     */
    atualizarQuantidade: function (event, incremento) {
        const button = event.currentTarget;
        const cardItem = button.closest('.cart-item');
        const inputQuantidade = cardItem.querySelector('.input-quantidade');
        const textPrecoTotal = cardItem.querySelector('.preco-total');
        
        // Atualiza a quantidade, aplicando o incremento
        const quantidadeAtual = parseInt(inputQuantidade.value);
        if (incremento < 0 && quantidadeAtual <= 1) return; // Impede diminuir a quantidade para valores negativos

        const novaQuantidade = Math.min(quantidadeAtual + incremento, inputQuantidade.max);
        inputQuantidade.value = novaQuantidade;

        // Calcula o novo preço total com base na quantidade e no preço unitário
        const precoUnitario = parseFloat(button.dataset.precoUnitario);
        const precoTotal = novaQuantidade * precoUnitario;
        textPrecoTotal.textContent = Carrinho.formatarMoeda(precoTotal);

        // Atualiza o preço total da compra
        const totalCompraAtual = parseFloat(document.querySelector('.total-compra').dataset.valorTotal);
        const totalCompraNovo = totalCompraAtual + (incremento * precoUnitario);
        
        // Atualiza o preço total da compra formatado
        document.querySelector('.total-compra').dataset.valorTotal = totalCompraNovo;
        document.querySelector('.total-compra').textContent = Carrinho.formatarMoeda(totalCompraNovo);
    },

    aumentarQuantidade: function (event) {
        Carrinho.atualizarQuantidade(event, 1);  // Aumenta a quantidade
    },

    diminuirQuantidade: function (event) {
        Carrinho.atualizarQuantidade(event, -1);  // Diminui a quantidade
    },

    realizarCheckout: async function(event) {
        // Impede o comportamento padrão do formulário (caso esteja sendo usado)
        event.preventDefault();
    
        // Desabilita o botão de envio para evitar múltiplos cliques
        const button = event.currentTarget;
        button.setAttribute('disabled', true);
    
        try {
            // Prepara os dados a serem enviados na requisição
            const formData = new FormData();

            // Adiciona os dados do formulário ao FormData
            document.querySelectorAll('.cart-item-selected').forEach((produto, index) => {
                formData.append(`produtos[${index}][IdProduto]`, produto.dataset.idProduto);
                formData.append(`produtos[${index}][Quantidade]`, produto.querySelector('.input-quantidade').value);
            });
            
            // Realiza a requisição para o servidor com fetch
            const response = await fetch('api.php?route=pedido/checkout', {
                method: 'POST',
                body: formData
            });
    
            // Converte a resposta em JSON
            const json = await response.json();
    
            // Verifica se a resposta contém a URL para redirecionamento
            if (json.status === 'ok') {
                // Redireciona o usuário para a URL fornecida
                window.location.replace(json.dados.redirecionar_url);
            } else {
                // Caso não haja URL, exibe um erro
                Alerta.erro('#alertaCarrinho', json.mensagem ||'Erro ao processar o checkout.');
            }
        } catch (error) {
            // Exibe o erro caso haja problemas durante a requisição
            console.error(error);
            Alerta.erro('#alertaCarrinho', 'Erro ao realizar checkout.');
        } finally {
            // Reabilita o botão após a requisição
            button.removeAttribute('disabled');
        }
    }    
};

document.addEventListener('DOMContentLoaded', () => {
    const btnAdicionarProduto = document.querySelectorAll('.btn-adicionar-produto');
    btnAdicionarProduto.forEach(btn => btn.addEventListener('click', Carrinho.adicionarProduto)); 
});
