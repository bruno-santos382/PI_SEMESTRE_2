function searchProduct() {
    const query = document.getElementById('searchInput').value.toLowerCase();
    const products = document.querySelectorAll('.card');
    products.forEach(product => {
        const title = product.querySelector('.card-title').textContent.toLowerCase();
        if (title.includes(query)) {
            product.style.display = 'block'; 
        } else {
            product.style.display = 'none'; 
        }
    });
} 
function scrollContainer(containerId, direction) {
    const container = document.getElementById(containerId);
    container.scrollBy({
        left: direction * 300, 
        behavior: 'smooth'
    });
}