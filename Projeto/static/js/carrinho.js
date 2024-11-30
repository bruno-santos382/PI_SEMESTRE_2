const Carrinho = {
    /**
     * Adiciona um produto ao carrinho de compras via AJAX.
     * 
     * @param {Event} event Evento de clique do botão "Adicionar ao carrinho".
     * @returns {Promise<void>}
     */
    adicionarProduto: async function (event) {
        this.setAttribute('disabled', true);
        const html = this.innerHTML;
        this.textContent = 'Carregando...';
    
        try {
            const dadosForm = new FormData();
            dadosForm.append('id_produto', this.dataset.idProduto);
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
        
        this.removeAttribute('disabled');
        this.innerHTML = html;
    }
}

document.addEventListener('DOMContentLoaded', () => {
    const btnAdicionarProduto = document.querySelectorAll('.btn-adicionar-produto');
    btnAdicionarProduto.forEach(btn => btn.addEventListener('click', Carrinho.adicionarProduto)); 
});