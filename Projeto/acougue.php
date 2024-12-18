<!-- Homepage do site -->
<?php

$template = array(
    'titulo' => 'Açougue &mdash; GM Supermercado',
    'scripts' => ['static/js/categorias.js', 'static/js/carrinho.js'],
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
    require_once __DIR__.'/src/class/produto/Produto.php';
    require_once __DIR__.'/src/class/categoria/Categoria.php';
    
    $produto = new Produto();
    $categoria = new Categoria();
?>

<div class="container my-5">
    <h2 class="text-center mb-4">Setor de Açougue</h2>

    <?php 
        // Categorias da página de Açougue
        foreach ($categoria->listarPorPagina('acougue') as $item) {
            $nome_categoria = $item['Nome'];
            include __DIR__ . '/src/template/lista_produtos_categoria.php';
        }
    ?>
</div>

<?php 
    include __DIR__ . '/src/template/popup_produto_adicionado.php';
    include __DIR__ . '/src/template/footer.php'; 
?>