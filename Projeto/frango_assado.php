<!-- Página do Frango Assado -->
<?php

$template = array(
	'titulo' => 'Frango Assado &mdash; GM Supermercado',
	'scripts' => ['static/js/frango_assado.js'],
	'styles' => ['static/css/frango.css']
);
include __DIR__ . '/src/template/header.php';

?>

<div class="container my-5">
	<h1 class="text-center mb-4">Faça seu Pedido de Frango Assado</h1>
	<p class="discount-note text-center">10% de desconto para pedidos online! Retire no local e aproveite o desconto.</p>

	<form action="" method="post" id="formPedido">
		<div class="form-group">
			<label for="nome">Nome Completo</label>
			<input type="text" class="form-control" id="nome" name="nome" required>
		</div>

		<div class="form-group">
			<label for="telefone">Telefone</label>
			<input type="tel" class="form-control" id="telefone" name="telefone" required>
		</div>

		<div class="form-group">
			<label for="quantidade">Quantidade de Frangos</label>
			<input type="number" class="form-control" id="quantidade" name="quantidade" min="1" value="1" required>
		</div>

		<div class="form-group">
			<label for="observacoes">Observações (opcional)</label>
			<textarea class="form-control" id="observacoes" name="observacoes" rows="3"></textarea>
		</div>

		<p class="text-danger font-weight-bold">Preço Unitário: R$ 34,99</p>
		<p class="text-success font-weight-bold">Com Desconto: R$ 31,49 por unidade</p>

		<button type="submit" class="btn btn-primary btn-lg btn-block">Confirmar Pedido para Retirada</button>
	</form>

	<!-- Google Maps -->
	<div class="text-center mt-4">
		<a href="https://www.google.com/maps/place/..." target="_blank" class="btn btn-info btn-lg">Ver Localização</a>
	</div>

	<p class="text-center mt-4"><strong>Nota:</strong> Todos os pedidos devem ser retirados no local até o horário de
		fechamento.</p>
</div>


<!-- Notificar que o pedido foi realizado -->
<div class="modal fade" id="modalPedidoRealizado" tabindex="-1" aria-labelledby="modalPedidoRealizadoLabel" role="dialog">
    <div class="modal-dialog  modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body text-center">
                <div id="checkIcon">
                    <i class="text-success fa-solid fa-circle-check" style="font-size: 64px;"></i>
                </div>
                <div class="mt-4 py-2">
					<h5 class="h5" id="modalPedidoRealizadoLabel">Pedido Realizado com Sucesso!</h5>
					<p class="text-secondary">Seu pedido foi confirmado. Entraremos em contato em breve.</p>
				</div>
                <div class="py-1">
                    <button type="button" class="btn btn-sm btn-outline-success rounded-pill px-5" data-bs-dismiss="modal">OK</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Notificar que ocorreu um erro ao realizar o pedido -->
<div class="modal fade" id="modalErroPedido" tabindex="-1" aria-labelledby="modalErroPedidoLabel" role="dialog">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body text-center">
                <div id="errorIcon">
                    <i class="text-danger fa-solid fa-circle-xmark" style="font-size: 64px;"></i>
                </div>
                <div class="mt-4 py-2">
                    <h5 class="h5" id="modalErroPedidoLabel">Erro ao Realizadr Pedido</h5>
                    <p class="text-secondary">Ocorreu um erro ao realizar seu pedido. Tente novamente.</p>
                </div>
                <div class="py-1">
                    <button type="button" class="btn btn-sm btn-outline-danger rounded-pill px-5" data-bs-dismiss="modal">Fechar</button>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/src/template/footer.php'; ?>
