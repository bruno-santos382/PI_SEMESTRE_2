<!-- Homepage do site -->
<?php

$template = array(
    'titulo' => 'Hortifruti &mdash; GM Supermercado',
    'scripts' => ['static/js/categorias.js'],
    'styles' => ['static/css/categorias.css']
);
include __DIR__ . '/src/template/header.php';

?>

<h2 class="text-center mb-4">Procurar por Produto</h2>

<!-- Barra de pesquisa -->
<div class="input-group mb-4">
    <input type="text" class="form-control" id="searchInput" placeholder="Digite o nome do produto" aria-label="Search">
    <button class="btn btn-outline-success" onclick="searchProduct()">Buscar</button>
</div>

<?php
    require __DIR__.'/src/class/produto/Produto.php';
    $produto = new Produto();
?>

<div class="container my-5">
    <h2 class="text-center mb-4">Setor de Hortifruti</h2>

    <!-- Categoria: Verduras -->
    <h3 class="mb-3">Verduras</h3>
    <div class="position-relative">
        <button class="scroll-button scroll-left" onclick="scrollContainer('verduras', -1)">&#10094;</button>
        <div class="scroll-container" id="verduras">
            <div class="card card-nenhum-resultado w-100" style="display: none">
                <div class="card-body text-center">
                    <h5>Nenhum Resultado Encontrado</h5>
                    <p>Desculpe, mas não encontramos produtos que correspondam à sua busca.</p>
                </div>
            </div>

            <?php foreach ($produto->listarPorCategoria('Verduras') as $item): ?>
                <div class="card card-produto">
                    <img src="<?= $item['Imagem'] ?>" class="card-img-top" alt="<?= $item['Nome'] ?>">
                    <div class="card-body text-center">
                        <h5 class="card-title"><?= $item['Nome'] ?></h5>
                        <p class="card-text"><?= $item['Marca'] ?></p>
                        <p class="text-danger"><strong>R$ <?= number_format($item['Preco'], 2, ',', '.') ?></strong></p>
                        <button href="#" class="btn btn-success">Adicionar ao Carrinho</button>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <button class="scroll-button scroll-right" onclick="scrollContainer('verduras', 1)">&#10095;</button>
    </div>
    <!--Seção Frutas-->
    <h3 class="mb-3">Frutas</h3>
    <div class="position-relative">
        <button class="scroll-button scroll-left" onclick="scrollContainer('frutas', -1)">&#10094;</button>
        <div class="scroll-container" id="frutas">
            <div class="card card-nenhum-resultado w-100" style="display: none">
                <div class="card-body text-center">
                    <h5>Nenhum Resultado Encontrado</h5>
                    <p>Desculpe, mas não encontramos produtos que correspondam à sua busca.</p>
                </div>
            </div>

            <?php foreach ($produto->listarPorCategoria('Frutas') as $item): ?>
                <div class="card card-produto">
                    <img src="<?= $item['Imagem'] ?>" class="card-img-top" alt="<?= $item['Nome'] ?>">
                    <div class="card-body text-center">
                        <h5 class="card-title"><?= $item['Nome'] ?></h5>
                        <p class="card-text"><?= $item['Marca'] ?></p>
                        <p class="text-danger"><strong>R$ <?= number_format($item['Preco'], 2, ',', '.') ?></strong></p>
                        <button href="#" class="btn btn-success">Adicionar ao Carrinho</button>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <button class="scroll-button scroll-right" onclick="scrollContainer('frutas', 1)">&#10095;</button>
    </div>

    <h3 class="mb-3">Legumes</h3>
    <div class="position-relative">
        <button class="scroll-button scroll-left" onclick="scrollContainer('legumes', -1)">&#10094;</button>
        <div class="scroll-container" id="legumes">
            <div class="card card-nenhum-resultado w-100" style="display: none">
                <div class="card-body text-center">
                    <h5>Nenhum Resultado Encontrado</h5>
                    <p>Desculpe, mas não encontramos produtos que correspondam à sua busca.</p>
                </div>
            </div>

             <?php foreach ($produto->listarPorCategoria('Legumes') as $item): ?>
                <div class="card card-produto">
                    <img src="<?= $item['Imagem'] ?>" class="card-img-top" alt="<?= $item['Nome'] ?>">
                    <div class="card-body text-center">
                        <h5 class="card-title"><?= $item['Nome'] ?></h5>
                        <p class="card-text"><?= $item['Marca'] ?></p>
                        <p class="text-danger"><strong>R$ <?= number_format($item['Preco'], 2, ',', '.') ?></strong></p>
                        <button href="#" class="btn btn-success">Adicionar ao Carrinho</button>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <button class="scroll-button scroll-right" onclick="scrollContainer('legumes', 1)">&#10095;</button>
    </div>
</div>

<?php include __DIR__ . '/src/template/footer.php'; ?>