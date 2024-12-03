<!-- Página do Carrinho -->
<?php

$template = array(
    'titulo' => 'Carrinho de Compras &mdash; GM Supermercado',
    'scripts' => ['static/js/carrinho.js'],
    'styles' => ['static/css/site.css']
);
include __DIR__ . '/src/template/header.php';

?>

<div class="w-50 mx-auto my-5">
    <h2 class="text-center">Carrinho de Compras</h2>

    <div id="alertaCarrinho" class="mb-3">
        <?php include __DIR__. '/src/template/alertas.php'; ?>
    </div>

    <?php 
        require_once __DIR__.'/src/class/carrinho/Carrinho.php';
        $carrinho = new Carrinho();
        ['valor_total' => $valor_total, 'produtos' => $produtos] = $carrinho->obterItens();
    ?>

    <div class="cart-empty mt-4 d-flex align-items-center justify-content-center <?php if (!empty($produtos)) echo 'd-none'; ?>" >
        <!-- Caso não haja produtos no carrinho, mostramos a mensagem -->
        <div class="text-center mt-4">
            <img src="static/img/carrinho_vazio.png" alt="Carrinho vazio" class="img-fluid" width="200">
            <h6 class="text-muted">O carrinho está vazio! 😞</h6>
            <p>Dê uma olhada em nosso catálogo e adicione seus produtos favoritos para começar a compra!</p>
            <a href="/" class="btn btn-primary mt-3">Ver Catálogo</a>
        </div>
    </div>

    <?php if (!empty($produtos)): ?>
        <div class="lista-produtos">
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
                <?php foreach (array_filter($produtos, fn($item) => $item['Estoque'] > 0) as $item): ?>
                    <div data-id-produto="<?= $item['IdProduto'] ?>" class="cart-item cart-item-selected d-grid justify-content-between align-items-center py-2 border-bottom" class="d-grid" style="grid-template-columns: 2fr 1fr 1fr 1fr 1fr;">
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
                        <div class="text-center">
                            <div class="d-flex align-items-center">
                                <button
                                    type="button"
                                    class="btn btn-sm btn-success me-2"
                                    title="Diminuir quantidade"
                                    data-preco-unitario="<?= $item['PrecoComDesconto'] ?? $item['Preco'] ?>"
                                    data-id-produto="<?= $item['IdProduto'] ?>"
                                    onclick="Carrinho.diminuirQuantidade(event)" >
                                    <i class="bi bi-dash h6"></i>
                                </button>
                                <input type="number" value="<?= $item['Quantidade'] ?>" class="form-control form-control-sm input-quantidade" style="width: 50px;" max="<?= $item['Estoque'] ?>" readonly>
                
                                <button
                                    type="button"
                                    class="btn btn-sm btn-success ms-2"
                                    title="Diminuir quantidade"
                                    data-preco-unitario="<?= $item['PrecoComDesconto'] ?? $item['Preco'] ?>"
                                    data-id-produto="<?= $item['IdProduto'] ?>"
                                    onclick="Carrinho.aumentarQuantidade(event)" >
                                    <i class="bi bi-plus h6"></i>
                                </button>
                            </div>
                            <small class="text-muted">Estoque: <?= $item['Estoque'] ?></small>
                        </div>
                        <!-- Preço unitário -->
                        <div class="text-center">
                            <?php if (!empty($item['PrecoComDesconto'])): ?>
                                <p class="text-success">
                                    <strong>R$ <?= number_format($item['PrecoComDesconto'], 2, ',', '.') ?></strong>
                                    <small class="text-danger" style="text-decoration: line-through;">R$ <?= number_format($item['Preco'], 2, ',', '.') ?></small>
                                </p>
                            <?php else: ?>
                                <strong class="text-muted">R$ <?= number_format($item['Preco'], 2, ',', '.') ?></strong>
                            <?php endif; ?>
                        </div>
                        <!-- Total do produto -->
                        <div class="text-center">
                            <strong class="preco-total">R$ <?= number_format($item['PrecoTotal'], 2, ',', '.') ?></strong>
                        </div>
                        <!-- Remover produto -->
                        <div class="text-center ">
                            <button
                                class="btn btn-sm btn-danger"
                                data-id-produto="<?= $item['IdProduto'] ?>"
                                data-preco-unitario="<?= $item['PrecoComDesconto'] ?? $item['Preco'] ?>"
                                onclick="Carrinho.removerProduto(event)" >
                                <i class="bi bi-trash-fill"></i> Remover
                            </button>
                        </div>
                    </div>
                <?php endforeach; ?>


                <!-- Exibição dos itens fora do estoque -->
                <?php foreach (array_filter($produtos, fn($item) => $item['Estoque'] < 1) as $item): ?>
            
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
                        <div class="text-center">
                            <span class="badge bg-danger">Fora de Estoque</span>
                        </div>

                        <div>&nbsp;</div>
                        <div>&nbsp;</div>

                        <!-- Remover produto -->
                        <div class="text-center justify-self-end ">
                            <button
                                class="btn btn-sm btn-danger"
                                data-id-produto="<?= $item['IdProduto'] ?>"
                                data-preco-unitario="<?= $item['PrecoComDesconto'] ?? $item['Preco'] ?>"
                                onclick="Carrinho.removerProduto(event)" >
                                <i class="bi bi-trash-fill"></i> Remover
                            </button>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <hr>
            
            <!-- Total do Carrinho -->
            <div class="d-flex justify-content-between align-items-center mt-4">
                <h4>Total:</h4>
                <h4 data-valor-total="<?= $valor_total ?>" class="total-compra">R$ <?= number_format($valor_total, 2, ',', '.') ?></h4>
            </div>

            <div class="text-center">
                <!-- Botão realizar pedido -->
                <button type="button" onclick="Carrinho.realizarCheckout(event)" class="btn btn-success mt-3" style="width: 150px;">Realizar pedido</button>

                <!-- Botão de esvaziar o carrinho -->
                <button type="button" class="btn btn-danger mt-3" style="width: 150px;" onclick="Carrinho.esvaziarCarrinho(event)">Esvaziar Carrinho</button>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php include __DIR__ . '/src/template/footer.php'; ?>