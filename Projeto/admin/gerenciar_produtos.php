<?php 

$template = array(
    'titulo' =>  'Gerenciar Produtoss &mdash; GM Supermercado',
    'menu_atual' => 'gerenciar_produtos',
    'scripts' => ['./static/js/admin/gerenciar_produtos.js']
);

include __DIR__ . '/../src/template/admin/header.php';
?>

<h2 class="text-center my-4">Gerenciar Produtos</h2>

<div id="alertaProduto">
    <?php include __DIR__. '/../src/template/alertas.php'; ?>
</div>

<table class="table table-centered table-hover table-borderless">
    <thead class="table-secondary">
        <tr>
            <th scope="col" style="width: 50px;">Código</th>
            <th scope="col">Categoria</th>
            <th scope="col" style="width: 100px;">Imagem</th>
            <th scope="col">Nome</th>
            <th scope="col">Marca</th>
            <th scope="col" style="width: 200px;">Preço</th>
            <th scope="col" style="width: 100px;">Estoque</th>
            <th scope="col" style="width: 200px;">Ações</th>
        </tr>
    </thead>
    <tbody>
        <?php 
            require_once __DIR__.'/../src/class/produto/Produto.php';
            $produto = new Produto();
        ?>
        <?php foreach ($produto->listarTudo() as $item): ?>
            <tr>
                <td><?= $item['IdProduto'] ?></td>
                <td><?= $item['Categoria'] ?></td>
                <td>
                    <img src="<?= $item['Imagem'] ?? 'static/img/galeria.png' ?>" alt="<?= $item['Nome'] ?>" class="img-fluid" style="max-width: 50px; height: auto;">
                </td>
                <td><?= $item['Nome'] ?></td>
                <td><?= $item['Marca'] ?></td>
                <td>R$ <?= number_format($item['Preco'], 2, ',', '.') ?></td>
                <td><?= $item['Estoque'] ?></td>
                <td>
                    <a href="admin/cadastro_produto.php?id=<?= $item['IdProduto'] ?>" class="btn btn-warning btn-sm" >
                        <i class="bi bi-pencil me-1"></i> Editar
                    </a>
                    <button type="button" class="btn btn-danger btn-sm" onclick="removerProduto(this, '<?= $item['IdProduto'] ?>')">
                        <i class="bi bi-trash me-1"></i> Remover
                    </button>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>



<?php include __DIR__ . '/../src/template/admin/footer.php'; ?>