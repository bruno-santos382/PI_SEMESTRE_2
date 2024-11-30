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