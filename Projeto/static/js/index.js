// Variáveis para o carrossel de promoções
const prevBtn = document.getElementById('prevBtn');
const nextBtn = document.getElementById('nextBtn');
const promoCarousel = document.querySelector('.promo-carousel');
const scrollAmount = 300; // Quantidade de rolagem do carrossel

// Rolagem do carrossel ao clicar nos botões
prevBtn.addEventListener('click', () => {
    promoCarousel.scrollBy({ left: -scrollAmount, behavior: 'smooth' });
});
nextBtn.addEventListener('click', () => {
    promoCarousel.scrollBy({ left: scrollAmount, behavior: 'smooth' });
});

