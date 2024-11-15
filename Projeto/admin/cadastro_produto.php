<?php 

$template = array(
    'titulo' =>  'Cadastro de Produtos &mdash; GM Supermercado',
    'menu_atual' => 'cadastro_produto',
    'scripts' => ['./static/js/admin/cadastro_produto.js']
);

include __DIR__ . '/../src/template/admin/header.php';
?>
<h2 class="text-center my-4">Cadastro de Produto</h2>

<div class="w-50 mx-auto">
    <form id="formCadastro" class="mb-3" action="" method="POST">
            <!-- Nome do Produto -->
            <div class="mb-3">
                <label for="nome" class="form-label fw-bold text-muted">Nome do Produto:</label>
                <input type="text" class="form-control" id="nome" name="nome" placeholder="Digite o nome do produto" required>
            </div>
            <!-- Marca -->
            <div class="mb-3">
                <label for="marca" class="form-label fw-bold text-muted">Marca:</label>
                <input type="text" class="form-control" id="marca" name="marca" placeholder="Digite a marca do produto" required>
            </div>
            <!-- Preço -->
            <div class="mb-3">
                <label for="preco" class="form-label fw-bold text-muted">Preço:</label>
                <input type="number" class="form-control" id="preco" name="preco" placeholder="R$" required>
            </div>
            <!-- Quantidade em Estoque -->
            <div class="mb-3">
                <label for="estoque" class="form-label fw-bold text-muted">Quantidade em Estoque:</label>
                <input type="number" class="form-control" id="estoque" name="estoque" placeholder="Digite a quantidade em estoque" required>
            </div>


            <!-- Botão de Cadastrar -->
            <div class="d-grid">
                <button type="submit" class="btn btn-primary btn-lg">Cadastrar</button>
            </div>
    </form>

    <?php include __DIR__. '/../src/template/alertas.php'; ?>   

</div>


<?php include __DIR__ . '/../src/template/admin/footer.php'; ?>