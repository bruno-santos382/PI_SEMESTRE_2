<?php 

$template = array(
    'titulo' =>  'Cadastro de Produtos &mdash; Admin',
    'scripts' => ['./static/js/admin/cadastro_produto.js']
);

include __DIR__ . '/../src/template/admin/header.php';
?>

<h1>Cadastro de Produto</h1>
<!-- Form to submit product details -->
<form id="formCadastro" action="" method="POST">
    <div>
        <label for="nome">Nome do Produto:</label>
        <input type="text" id="nome" name="nome" required>
    </div>

    <div>
        <label for="preco">Preço:</label>
        <input type="text" id="preco" name="preco" required>
    </div>

    <div>
        <label for="marca">Marca:</label>
        <input type="text" id="marca" name="marca" required>
    </div>

    <div>
        <label for="estoque">Quantidade em Estoque:</label>
        <input type="number" id="estoque" name="estoque" required>
    </div>

    <div>
        <button type="submit">Cadastrar</button>
    </div>
</form>

<?php include __DIR__ . '/../src/template/admin/footer.php'; ?>