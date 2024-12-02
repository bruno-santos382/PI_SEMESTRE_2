<?php

$template = array(
	'titulo' => 'Pedidos &mdash; GM Supermercado',
	'menu_atual' => 'pedidos',
	'scripts' => ['./static/js/admin/pedidos.js'],
	'styles' => ['./static/css/admin/pedidos.css']
);

include __DIR__ . '/../src/template/admin/header.php';
?>

<h2 class="text-center my-4">Gerenciar Pedidos</h2>

<div id="alertaPedido" class="mb-3">
	<?php include __DIR__. '/../src/template/alertas.php'; ?>
</div>

<div id="alertaEditando" class="slide-down mb-2">
	<span class="text-success">
		<i class="bi bi-pencil-fill-square"></i> Editando pedido número <strong id="pedidoSelecionado"></strong>
	</span>
</div>

<!-- Formulário para gerenciar pedidos -->
<form id="formCadastro" action="#" method="POST" class="mb-5">
	<input type="number" name="id" id="codigo" hidden value="">

	<?php
		require __DIR__.'/../src/class/cliente/Cliente.php';
		$cliente = new Cliente();
	?>

	<div class="row align-items-end g-3">
		<div class="col">
			<label for="cliente" class="form-label">Selecionar Cliente:</label>
			<select id="cliente" name="cliente" class="form-select" required>
				<option value="">Selecione um cliente</option>

				<?php foreach ($cliente->listarTudo() as $item): ?>
					<option value="<?= $item['IdCliente'] ?>"><?= $item['Nome'] ?></option>
				<?php endforeach; ?>
			</select>
		</div>

		<div class="col">
			<label for="dataPedido" class="form-label">Data do Pedido:</label>
			<input type="date" id="dataPedido" name="data_pedido" class="form-control" required>
		</div>

		<div class="col">
			<label for="dataRetirada" class="form-label">Data de Retirada:</label>
			<input type="date" id="dataRetirada" name="data_retirada" class="form-control">
		</div>

		<div class="col">
			<label for="valorTotal" class="form-label">Valor Total:</label>
			<input type='number' id='valorTotal' name='valor_total' step='.01' min='.01' placeholder='R$'
				class='form-control' required>
		</div>

		<!-- Botão para salvar o pedido -->
		<div class="text-center">
			<button type='submit' class="btn btn-primary w-50">Salvar Pedido</button>
		</div>
	</div>
</form>

<hr>

<!-- Botões para alternar entre Pedidos em Andamento, Finalizados e Cancelados -->
<ul class="nav nav-tabs justify-content-center mt-5 mb-3 gap-3" role="tablist">
    <li role="presentation">
        <a href="#pedidos-andamento" class="active" id="abas-pedidos-andamento" role="tab" data-bs-toggle="tab" aria-controls="pedidos-andamento" aria-selected="true">
            <button type="button" class="btn btn-primary">Pedidos em Andamento</button>
        </a>
    </li>
    <li role="presentation">
        <a href="#pedidos-finalizados" class="" id="abas-pedidos-finalizados" role="tab" data-bs-toggle="tab" aria-controls="pedidos-finalizados" aria-selected="false">
            <button type="button" class="btn btn-success">Pedidos Finalizados</button>
        </a>
    </li>
    <li role="presentation">
        <a href="#pedidos-cancelados" class="" id="abas-pedidos-cancelados" role="tab" data-bs-toggle="tab" aria-controls="pedidos-cancelados" aria-selected="false">
            <button type="button" class="btn btn-danger">Pedidos Cancelados</button>
        </a>
    </li>
</ul>

<?php
    require_once __DIR__.'/../src/class/pedido/Pedido.php';
    $pedido = new Pedido();
?>

<div class="tab-content" id="conteudoAbasPedidos">
    <!-- Abas para Pedidos em Andamento -->
    <div class="tab-pane fade show active" id="pedidos-andamento" role="tabpanel" aria-labelledby="abas-pedidos-andamento">
        <h3 class="text-center my-4">Pedidos em Andamento</h3>
        <?php
        // Consulta para pedidos em andamento
		$template['status_pedido'] = 'Em Andamento';
        $template['acoes_pedido'] = true;
		$template['pedidos'] =  $pedido->listarPedidosEmAndamento(); // Método para listar pedidos em andamento
        // Inclui o template da tabela com os pedidos em andamento
        include __DIR__ . '/../src/template/admin/tabela_pedidos.php';
        ?>
    </div>

    <!-- Abas para Pedidos Finalizados -->
    <div class="tab-pane fade" id="pedidos-finalizados" role="tabpanel" aria-labelledby="abas-pedidos-finalizados">
        <h3 class="text-center my-4">Pedidos Finalizados</h3>
        <?php
        // Consulta para pedidos finalizados
		$template['status_pedido'] = 'Finalizado';
        $template['acoes_pedido'] = false;
        $template['pedidos'] =  $pedido->listarPedidosFinalizados(); // Método para listar pedidos finalizados
        // Inclui o template da tabela com os pedidos finalizados
        include __DIR__ . '/../src/template/admin/tabela_pedidos.php';
        ?>
    </div>

    <!-- Abas para Pedidos Cancelados -->
    <div class="tab-pane fade" id="pedidos-cancelados" role="tabpanel" aria-labelledby="abas-pedidos-cancelados">
        <h3 class="text-center my-4">Pedidos Cancelados</h3>
        <?php
        // Consulta para pedidos cancelados
		$template['status_pedido'] = 'Cancelado';
        $template['acoes_pedido'] = false;
        $template['pedidos'] =  $pedido->listarPedidosCancelados(); // Método para listar pedidos cancelados
        // Inclui o template da tabela com os pedidos cancelados
        include __DIR__ . '/../src/template/admin/tabela_pedidos.php';
        ?>
    </div>
</div>

<?php include __DIR__ . '/../src/template/admin/modal_detalhes_pedido.php'; ?>
<?php include __DIR__ . '/../src/template/admin/modal_lista_itens_pedido.php'; ?>
<?php include __DIR__ . '/../src/template/admin/footer.php'; ?>
