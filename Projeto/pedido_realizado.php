<!-- Página de Pedido Realizado -->
<?php

$template = array(
    'titulo' => 'Pedido Realizado &mdash; GM Supermercado',
    'scripts' => ['static/js/checkout.js'],
    'styles' => ['static/css/site.css']
);
include __DIR__ . '/src/template/header.php';

// Ensure $pedido_id is set (from the checkout process)
$pedido_id = filter_input(INPUT_GET, 'pedido', FILTER_VALIDATE_INT);

?>

<div class="container w-75 mx-auto">
    <h2 class="text-center">Pedido Realizado</h2>

    <div id="alertaCheckout" class="mb-3">
        <?php include __DIR__ . '/src/template/alertas.php'; ?>
    </div>

    <!-- Pedido Gerado com Sucesso -->
    <div class="pedido-sucesso mt-4 d-flex align-items-center justify-content-center">
        <!-- Caso o pedido tenha sido gerado com sucesso, mostramos a mensagem -->
        <div class="text-center mt-4">
            <img src="static/img/sucesso.png" alt="Pedido gerado com sucesso" class="img-fluid" width="200">
            <h5 class="text-success">Pedido #<?= $pedido_id ?> gerado com sucesso!</h5>
            <!-- <p>Seu pedido foi processado com sucesso. Você receberá atualizações em breve.</p> -->
            <p class="text-muted"><span>💡 Lembre-se de salvar seu comprovante para apresentar no mercado. Obrigado!</span></p>
            <a href="comprovante_pedido.php?pedido=<?= $pedido_id ?>" target="_blank" class="btn btn-primary mt-3">Ver Comprovante</a>
            <a href="/" class="btn btn-secondary mt-3">Voltar ao Catálogo</a>
        </div>
    </div>

</div>

<?php include __DIR__ . '/src/template/footer.php'; ?>
