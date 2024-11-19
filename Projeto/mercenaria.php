<!-- Homepage do site -->
<?php

$template = array(
    'titulo' => 'Mercenaria &mdash; GM Supermercado',
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
    <h2 class="text-center mb-4">Setor de Mercenaria</h2>

    <!-- Categoria: Grãos -->
    <?php 
        $categoria = 'Grãos e Cereais';
        include __DIR__.'/src/template/lista_produtos_categoria.php';
    ?>
</div>

<?php 
    include __DIR__ . '/src/template/popup_produto_adicionado.php';
    include __DIR__ . '/src/template/footer.php'; 
?>