<!-- Página de Checkout -->

<?php

// Apenas clientes podem acessar
require_once __DIR__.'/src/class/autenticacao/Autentica.php';
$autentica = new Autentica();
$usuario = $autentica->usuarioLogado();
if ($usuario && $usuario['tipo'] !== 'cliente') {
    header('Location: index.php');
    exit;
}

?>

<?php

$template = array(
    'titulo' => 'Checkout &mdash; GM Supermercado',
    'scripts' => ['static/js/checkout.js'],
    'styles' => ['static/css/site.css']
);
include __DIR__ . '/src/template/header.php';

?>

<div class="container w-75 mx-auto">
    <h2 class="text-center">Checkout</h2>

    <div id="alertaCheckout" class="mb-3">
        <?php include __DIR__ . '/src/template/alertas.php'; ?>
    </div>

    <?php
    require __DIR__ . '/src/class/pedido/Pedido.php';
    $pedido = new Pedido();
    ['valor_total' => $valor_total, 'produtos' => $produtos] = $pedido->listaItensCheckout();

    if (empty($produtos)) {
        echo '<div class="text-center mt-5">
                    <h4 class="text-muted">Seu carrinho está vazio! 😞</h4>
                    <p><a href="/" class="btn btn-primary mt-3">Voltar ao Catálogo</a></p>
                  </div>';
        include __DIR__ . '/src/template/footer.php';
        exit;
    }
    ?>

    <form id="formCheckout" method="POST" action="">
        <div class="row">
            <?php 
                require_once __DIR__ . '/src/class/autenticacao/Autentica.php';
                $autentica = new Autentica();
                $usuario = $autentica->usuarioLogado(); 
            ?>

            <!-- Coluna para detalhes do cliente -->
            <div class="col-md-6 border-end pe-4">
                <h5 class="mb-5">Informações do cliente:</h5>
                <div class="mb-3">
                    <label for="nome" class="form-label">Nome Completo</label>
                    <input type="text" name="nome" id="nome" class="form-control" value="<?= $usuario['nome'] ?>" readonly required>
                    <input type="hidden" name="id_cliente" value="<?= $usuario['id_pessoa'] ?>">
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">E-mail</label>
                    <input type="email" name="email" id="email" class="form-control" value="<?= $usuario['email'] ?>" readonly required>
                </div>
                <div class="mb-3">
                    <label for="telefone" class="form-label">Telefone</label>
                    <input type="text" name="telefone" id="telefone" class="form-control" value="<?= $usuario['telefone'] ?>" readonly required>
                </div>

                <div class="mb-3">
                    <label for="metodoPagamento" class="form-label">Método de Pagamento</label>
                    <select name="metodo_pagamento" id="metodoPagamento" class="form-select" required>
                        <option value="">Selecione um método</option>
                        <option value="Pix">Pix</option>
                        <option value="Cartao">Cartão de crédito</option>
                        <option value="Dinheiro">Dinheiro</option>
                    </select>
                    <small class="text-muted">O pagamento será realizado no momento de retirada/entrega dos
                        produtos.</small>
                </div>

                <!-- Checkbox para retirar no mercado -->
                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" id="semEntrega" name="sem_entrega"
                        data-bs-toggle="collapse" data-bs-target="#dadosEntregaCollapse" aria-expanded="true"
                        aria-controls="dadosEntregaCollapse">
                    <label class="form-check-label" for="semEntrega">
                        Retirar no Mercado
                    </label>
                </div>

                <!-- Campos de endereço com collapse -->
                <div id="dadosEntregaCollapse" class="collapse show">
                    <div class="mb-3">
                        <label for="endereco" class="form-label">Endereço</label>
                        <input name="endereco" id="endereco" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label for="dataEntrega" class="form-label">Data de Entrega</label>
                        <input type="date" name="data_entrega" id="dataEntrega" class="form-control"
                            min="<?= date('Y-m-d') ?>" value="<?= date('Y-m-d') ?>">
                        <small class="text-muted">
                            A data de entrega poderá ser ajustada conforme disponibilidade.
                        </small>
                    </div>
                </div>
            </div>

            <!-- Coluna para produtos e total -->
            <div class="col-md-6 ps-4">
                <h5>Produtos no seu pedido:</h5>
                <div class="lista-produtos mt-4">
                    <div class="border-bottom py-2 d-grid" style="grid-template-columns: 2fr 1fr 1fr 1fr;">
                        <div><strong>Produto</strong></div>
                        <div class="text-center"><strong>Quantidade</strong></div>
                        <div class="text-center"><strong>Preço Unitário</strong></div>
                        <div class="text-center"><strong>Total</strong></div>
                    </div>

                    <div class="overflow-y-auto" style="height: 48.5vh;">
                        <?php foreach ($produtos as $index => $item): ?>
                            <input type="hidden" name="<?= "produtos[$index][IdProduto]" ?>" value="<?= $item['IdProduto'] ?>">
                            <input type="hidden" name="<?= "produtos[$index][Quantidade]" ?>" value="<?= $item['Quantidade'] ?>">
                            <input type="hidden" name="<?= "produtos[$index][Preco]" ?>" value="<?= $item['PrecoComDesconto'] ?? $item['Preco'] ?>
                            ">
                            <div class="cart-item cart-item-selected d-grid align-items-center py-2 border-bottom"
                                style="grid-template-columns: 2fr 1fr 1fr 1fr;">
                                <div class="d-flex align-items-center">
                                    <!-- Imagem do produto -->
                                    <img src="<?= $item['Imagem'] ?? 'static/img/galeria.png' ?>" alt="<?= $item['Nome'] ?>"
                                        class="img-fluid" style="width: 60px; height: 60px;">
                                    <div class="ms-3">
                                        <!-- Nome do produto -->
                                        <h5 class="mb-0"><?= $item['Nome'] ?></h5>
                                        <small class="text-muted"><?= $item['Marca'] ?></small>
                                    </div>
                                </div>
                                <div class="text-center"><?= $item['Quantidade'] ?></div>
                                <div class='text-center'>R$
                                    <?= number_format($item['PrecoComDesconto'] ?? $item['Preco'], 2, ',', '.') ?></div>
                                <div class='text-center'><strong>R$
                                        <?= number_format($item['PrecoTotal'], 2, ',', '.') ?></strong></div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Total do Pedido -->
                <hr />
                <div class='d-flex justify-content-between align-items-center mt-4'>
                    <h4>Total:</h4>
                    <h4>R$ <?= number_format($valor_total, 2, ',', '.') ?></h4>
                    <input type="hidden" name="total" value="<?= $valor_total ?>">
                </div>

                <div class="mt-3">
                    <button type="submit" class="btn btn-success">Confirmar Pedido</button>
                    <a href="/carrinho.php" class="btn btn-secondary">Voltar ao Carrinho</a>
                </div>
            </div>
        </div>
    </form>
</div>

<?php include __DIR__ . '/src/template/footer.php'; ?>