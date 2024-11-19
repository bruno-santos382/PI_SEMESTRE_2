<!-- Homepage do site -->
<?php

$template = array(
    'titulo' => 'Loja Online &mdash; GM Supermercado',
    'scripts' => ['static/js/index.js'],
    'styles' => ['static/css/site.css']
);
include __DIR__ . '/src/template/header.php';

?>

<!-- Categorias -->
<div class="container mt-5">
    <h2 class="text-center mb-4">Categorias</h2>
    <div class="row text-center justify-content-center align-items-center">
        <div class="col-4 col-md-2">
            <a href="hortifruti.php" class="category-link">
                <div class="category-item">
                    <div class="icon-circle">
                        <img src="static/img/frutas.png" alt="Frutas" class="category-image">
                    </div>
                    <p>HortiFruti</p>
                </div>
            </a>
        </div>
        <div class="col-4 col-md-2">
            <a href="acougue.php" class="category-link">
                <div class="category-item">
                    <div class="icon-circle">
                        <img src="static/img/carne.png" alt="Carne" class="category-image">
                    </div>
                    <p>Açougue</p>
                </div>
            </a>
        </div>
        <div class="col-4 col-md-2">
            <a href="mercenaria.php" class="category-link">
                <div class="category-item">
                    <div class="icon-circle">
                        <img src="static/img/MERCENARIA.png" alt="mercenaria" class="category-image">
                    </div>
                    <p>Mercenaria</p>
                </div>
            </a>
        </div>
        <div class="col-4 col-md-2">
            <a href="bebida.php" class="category-link">
                <div class="category-item">
                    <div class="icon-circle">
                        <img src="static/img/bebida.png" alt="Bebidas" class="category-image">
                    </div>
                    <p>Bebidas</p>
                </div>
            </a>
        </div>
        <div class="col-4 col-md-2">
            <a href="padaria.php" class="category-link">
                <div class="category-item">
                    <div class="icon-circle">
                        <img src="static/img/pao.png" alt="Padaria" class="category-image">
                    </div>
                    <p>Padaria</p>
                </div>
            </a>
        </div>
        <div class="col-4 col-md-2">
            <a href="limpeza.php" class="category-link">
                <div class="category-item">
                    <div class="icon-circle">
                        <img src="static/img/limpeza.png" alt="Limpeza" class="category-image">
                    </div>
                    <p>Limpeza</p>
                </div>
            </a>
        </div>
    </div>
</div>
<!-- FIM Categorias -->
<!-- Promoção -->


<header class="promo-header py-4" style="background-color: #FCBF49;">
    <div class="container position-relative">
        <h2 class="text-center mb-4">Promoções Da Semana</h2>

        <button id="prevBtn" class="scroll-btn left-btn"><span class="carousel-control-prev-icon"></span></button>
        <button id="nextBtn" class="scroll-btn right-btn"><span class="carousel-control-next-icon"></span></button>

        <div class="promo-carousel d-flex overflow-hidden">

            <div class="card h-100 mx-2">
                <img src="static/img/upload/cenoura.png" class="card-img-top" alt="Cenoura">
                <div class="card-body">
                    <h5 class="card-title">Cenoura</h5>
                    <p class="card-text">Cenoura fresca - 1kg</p>
                    <p class="text-danger"><strong>R$ 14,99</strong> <small class="text-muted">R$ 19,99</small></p>
                    <button class="btn btn-success">Adicionar ao Carrinho</button>
                </div>
            </div>

            <div class="card h-100 mx-2">
                <img src="static/img/upload/suco.jpg" class="card-img-top" alt="Suco Natural">
                <div class="card-body">
                    <h5 class="card-title">Suco Natural</h5>
                    <p class="card-text">Suco de Laranja - 1L</p>
                    <p class="text-danger"><strong>R$ 5,99</strong> <small class="text-muted">R$ 7,99</small></p>
                    <button class="btn btn-success">Adicionar ao Carrinho</button>
                </div>
            </div>

            <div class="card h-100 mx-2">
                <img src="static/img/upload/mamao.webp" class="card-img-top" alt="Mamão">
                <div class="card-body">
                    <h5 class="card-title">Mamão</h5>
                    <p class="card-text">Mamão Fresco - 5kg</p>
                    <p class="text-danger"><strong>R$ 12,99</strong> <small class="text-muted">R$ 15,99</small></p>
                    <button class="btn btn-success">Adicionar ao Carrinho</button>
                </div>
            </div>

            <div class="card h-100 mx-2">
                <img src="static/img/upload/morangos1.jpg" class="card-img-top" alt="Morango">
                <div class="card-body">
                    <h5 class="card-title">Morango</h5>
                    <p class="card-text">Morango fresco - 500g</p>
                    <p class="text-danger"><strong>R$ 8,99</strong> <small class="text-muted">R$ 10,99</small></p>
                    <button class="btn btn-success">Adicionar ao Carrinho</button>
                </div>
            </div>

            <div class="card h-100 mx-2">
                <img src="static/img/upload/tomate.jpg" class="card-img-top" alt="Tomate">
                <div class="card-body">
                    <h5 class="card-title">Tomate</h5>
                    <p class="card-text">Tomate fresco - 1kg</p>
                    <p class="text-danger"><strong>R$ 6,99</strong> <small class="text-muted">R$ 8,99</small></p>
                    <button class="btn btn-success">Adicionar ao Carrinho</button>
                </div>
            </div>

            <div class="card h-100 mx-2">
                <img src="static/img/upload/carnemoida.webp" class="card-img-top" alt="Carne Moída">
                <div class="card-body">
                    <h5 class="card-title">Carne Moída</h5>
                    <p class="card-text">Carne Moída - 1kg</p>
                    <p class="text-danger"><strong>R$ 24,99</strong> <small class="text-muted">R$ 29,99</small></p>
                    <button class="btn btn-success">Adicionar ao Carrinho</button>
                </div>
            </div>
        </div>
    </div>

</header>
<!-- Fimmm Promoçoes -->

<!-- JavaScript -->
<script src="js/index.js" defer></script>
<!--Promoçao Frango-->
<div class="container my-5 py-4 border rounded text-center" style="background-color: #FCBF49;">
    <h2 class="text-center mb-4">Domingo é dia de Frango!</h2>
    <div class="row justify-content-center align-items-center">
        <div class="col-md-5">
            <img src="static/img/upload/frango.jpg" alt="Frango Assado" class="img-fluid rounded" style="width: 100%; height: auto;">
        </div>
        <div class="col-md-7">
            <h4 class="mt-3" style="color: #8B4513;">Encomende seu Frango Assado!</h4>
            <p>Garanta o frango assado fresquinho para seu almoço de domingo! Encomendas abertas até sábado às 18h.</p>
            <p class="text-danger"><strong>R$ 34,99</strong> <small class="text-muted">por unidade</small></p>

            <!-- Promoção especial para pedidos online -->
            <p class="text-success mt-3"><strong>Promoção Especial:</strong> Desconto de 10% para pedidos online!</p>

            <!-- Botão de encomenda -->
            <a href="paginapedidosfrango.html" class="btn btn-primary btn-lg mt-3">Encomendar Agora e Ganhe o
                Desconto!</a>
        </div>
    </div>
</div>
<!-- Ajuda e Suporte -->
<footer class="footer">
    <div class="container">
        <div class="row">
            <div class="col-md-2">
                <h5>Ajuda e Suporte</h5>
                <ul class="list-unstyled">
                    <li><a href="#" onclick="openFAQ()">FAQ</a></li>
                    <li><a href="https://wa.me/55XX999999999" target="_blank">Suporte via WhatsApp</a></li>
                    <li><a href="mailto:contato@gmsupermercado.com">Envie um Email</a></li>
                </ul>
            </div>
            <div class="col-md-2">
                <h5>Contato</h5>
                <ul class="list-unstyled">
                    <li><a href="tel:+55XX999999999">Telefone: (XX) 9999-9999</a></li>
                    <li><a href="mailto:contato@gmsupermercado.com">Email: @gmsupermercado.com</a></li>
                </ul>
            </div>
            <div class="col-md-2">
                <h5>Localização</h5>
                <ul class="list-unstyled">
                    <li><a href="https://goo.gl/maps/example" target="_blank">Ver no Google Maps</a></li>
                    <li>Rua Exemplo, 123</li>
                    <li>Cidade, Estado</li>
                </ul>
            </div>
            <div class="col-md-3">
                <h5>Sobre Nós</h5>
                <ul class="list-unstyled">
                    <li><a href="sobre.html">Nossa História</a></li>
                    <li><a href="valores.html">Missão e Valores</a></li>
                    <li><a href="equipe.html">Nossa Equipe</a></li>
                </ul>
            </div>
            <div class="col-md-3">
                <h5>Siga-nos</h5>
                <ul class="list-unstyled social-icons">
                    <li><a href="https://facebook.com" target="_blank"><i class="fab fa-facebook"></i> Facebook</a></li>
                    <li><a href="https://instagram.com" target="_blank"><i class="fab fa-instagram"></i> Instagram</a>
                    </li>
                    <li><a href="https://twitter.com" target="_blank"><i class="fab fa-twitter"></i> Twitter</a></li>
                </ul>
            </div>
        </div>
        <p class="mt-3">© 2023 GM SuperMercado. Todos os direitos reservados.</p>
    </div>
</footer>

<?php include __DIR__ . '/src/template/footer.php'; ?>