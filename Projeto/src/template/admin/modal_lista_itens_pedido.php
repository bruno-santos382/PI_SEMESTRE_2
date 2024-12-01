<!-- Modal -->
<div class="modal fade" id="modalItensPedido" tabindex="-1" aria-labelledby="modalItensPedidoLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="modalItensPedidoLabel">Itens do Pedido</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<div class="pe-3">
					<table class="table table-borderless">
						<colgroup>
							<col>
							<col width="150px">
							<col width="150px">
							<col width="150px">
						</colgroup>
						<thead>
							<tr>
								<th>Produto</th>
								<th class="text-center">Quantidade</th>
								<th class="text-center">Preço Unitário</th>
								<th class="text-center">Total</th>
							</tr>
						</thead>
					</table>
				</div>

				<div class="overflow-y-scroll" style="height: 55vh;">

					<div class="d-flex align-items-center justify-content-center h-100" id="carregando-itens-pedido">
						<div class="spinner-border text-primary" role="status">
							<span class="visually-hidden">Carregando...</span>
						</div>
						<span class="ms-2">Carregando...</span>
					</div>

					<table class="table table-borderless d-none" id="lista-itens-pedido">
						<colgroup>
							<col>
							<col width="150px">
							<col width="150px">
							<col width="150px">
						</colgroup>
						<tbody>
						</tbody>
					</table>
				</div>


				<hr>

				<!-- Total do Pedido -->
				<div class="d-flex justify-content-between align-items-center mt-4 px-2">
					<h5>Total:</h5>
					<h5 data-valor-total="5.16" id="total-compra-pedido">R$ 0,00</h5>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
				<!-- Botão de confirmação, se necessário -->
				<!-- <button type="button" class="btn btn-primary">Confirmar</button> -->
			</div>
		</div>
	</div>
</div>