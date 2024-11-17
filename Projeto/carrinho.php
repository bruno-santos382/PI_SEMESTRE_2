<!-- Homepage do site -->
<?php

$template = array(
    'titulo' => 'Carrinho de Compras &mdash; GM Supermercado',
    'scripts' => ['static/js/site.js'],
    'styles' => ['static/css/site.css']
);
include __DIR__ . '/src/template/header.php';

?>

<div class="w-50 mx-auto mt-5">
    <h2 class="text-center">Carrinho de Compras</h2>

    <?php 
        require __DIR__.'/src/class/carrinho/Carrinho.php';
        $carrinho = new Carrinho();
        ['valor_total' => $valor_tota, 'produtos' => $produtos] = $carrinho->obterItens();
    ?>
    
    <?php if (empty($produtos)): ?>
        <div class="mt-4 d-flex align-items-center justify-content-center" >
            <!-- Caso não haja produtos no carrinho, mostramos a mensagem -->
            <div class="text-center mt-4">
                <img src="static/img/carrinho_vazio.png" alt="Carrinho vazio" class="img-fluid" width="200">
                <h6 class="text-muted">O carrinho está vazio! 😞</h6>
                <p>Dê uma olhada em nosso catálogo e adicione seus produtos favoritos para começar a compra!</p>
                <a href="/" class="btn btn-primary mt-3">Ver Catálogo</a>
            </div>
        </div>
    <?php else: ?>
        <div class="mt-4">
            <div class="border-bottom py-2 d-grid" style="grid-template-columns: 2fr 1fr 1fr 1fr 1fr;">
                <!-- Cabeçalhos das colunas -->
                <div >
                    <strong>Produto</strong>
                </div>
                <div class="text-center">
                    <strong>Quantidade</strong>
                </div>
                <div class="text-center">
                    <strong>Preço Unitário</strong>
                </div>
                <div class="text-center">
                    <strong>Total</strong>
                </div>
                <div class="text-center">
                    <strong>Ação</strong>
                </div>
            </div>

            <!-- Exibição dos itens do carrinho -->
            <?php foreach ($produtos as $item): ?>
                
                <div class="cart-item d-grid justify-content-between align-items-center py-2 border-bottom" class="d-grid" style="grid-template-columns: 2fr 1fr 1fr 1fr 1fr;">
                    <div class="d-flex align-items-center">
                        <!-- Imagem do produto -->
                        <img src="<?= $item['Imagem'] ?? 'static/img/galeria.png' ?>" alt="<?= $item['Nome'] ?>" class="img-fluid" style="width: 60px; height: 60px;">
                        <div class="ms-3">
                            <!-- Nome do produto -->
                            <h5 class="mb-0"><?= $item['Nome'] ?></h5>
                            <small class="text-muted"><?= $item['Marca'] ?></small>
                        </div>
                    </div>

                    <!-- Quantidade -->
                    <div class="quantity d-flex align-items-center">
                        <button type="button" class="btn btn-sm btn-success me-2" title="Diminuir quantidade" onclick="decreaseQuantity(productId)">
                            <i class="bi bi-dash h6"></i>
                        </button>

                        <input type="number" value="<?= $item['Quantidade'] ?>" class="form-control form-control-sm" style="width: 50px;" readonly>
                        
                        <button type="button" class="btn btn-sm btn-success ms-2" title="Aumentar quantidade" onclick="increaseQuantity(productId)">
                            <i class="bi bi-plus h6"></i>
                        </button>
                    </div>

                    <!-- Preço unitário -->
                    <div class="text-center">
                        <span class="text-muted">R$ <?= number_format($item['Preco'], 2, ',', '.') ?></span>
                    </div>

                    <!-- Total do produto -->
                    <div class="text-center">
                        <strong>R$ <?= number_format($item['Preco'] * $item['Quantidade'], 2, ',', '.') ?></strong>
                    </div>

                    <!-- Remover produto -->
                    <div class="text-center ">
                        <button class="btn btn-sm btn-danger" onclick="removeItem(productId)">
                            <i class="bi bi-trash"></i> Remover
                        </button>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Total do Carrinho -->
        <div class="d-flex justify-content-between align-items-center mt-4">
            <h4>Total:</h4>
            <span><strong>R$ <?= number_format($valor_tota, 2, ',', '.') ?></strong></span>
        </div>

        <!-- Botão de esvaziar o carrinho -->
        <button type="button" class="btn btn-danger mt-3" onclick="clearCart()">Esvaziar Carrinho</button>
    </div>
<?php endif; ?>

<?php include __DIR__ . '/src/template/footer.php'; ?>