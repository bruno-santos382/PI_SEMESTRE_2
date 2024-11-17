function searchProduct() {
    const query = document.getElementById('searchInput').value.toLowerCase();
    const products = document.querySelectorAll('.card-produto');
    products.forEach(product => {
        const title = product.querySelector('.card-title').textContent.toLowerCase();
        if (title.includes(query)) {
            product.style.display = 'block'; 
        } else {
            product.style.display = 'none'; 
        }
    });

    const containers = document.querySelectorAll('.scroll-container');
    containers.forEach(container => {
        const algumProdutoVisivel = Array.from(container.querySelectorAll('.card-produto')).some(card => card.style.display === 'block');
        const nenhumProduto = container.querySelector('.card-nenhum-resultado');
        nenhumProduto.style.display = algumProdutoVisivel ? 'none' : 'block';
    });
} 
function scrollContainer(containerId, direction) {
    const container = document.getElementById(containerId);
    container.scrollBy({
        left: direction * 300, 
        behavior: 'smooth'
    });
}

async function adicionarProduto(event) {
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

document.addEventListener('DOMContentLoaded', () => {
    const btnAdicionarProduto = document.querySelectorAll('.btn-adicionar-produto');
    btnAdicionarProduto.forEach(btn => btn.addEventListener('click', adicionarProduto)); 
});