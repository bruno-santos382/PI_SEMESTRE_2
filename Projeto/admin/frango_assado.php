<?php

$template = array(
    'titulo' => 'Pedidos de Frango Assado &mdash; GM Supermercado',
    'menu_atual' => 'frango_assado',
    'scripts' => ['./static/js/admin/frango_assado.js'],
    'styles' => ['./static/css/admin/frango_assado.css']
);

include __DIR__ . '/../src/template/admin/header.php';
?>

<h2 class="text-center my-4">Gerenciar Pedidos de Frango Assado</h2>

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
    <div class="row align-items-end g-3">
        <div class="col">
            <label for="cliente" class="form-label">Cliente:</label>
            <input type="text" name="nome" id="cliente" class="form-control">
        </div>

        <div class="col">
            <label for="telefone" class="form-label">Telefone:</label>
            <input type="text" name="telefone" id="telefone" class="form-control">
        </div>

        <div class="col">
            <label for="observacoes" class="form-label">Observações:</label>
            <input type="text" id="observacoes" name="observacoes" class="form-control" maxlength="255">
        </div>
    </div>

    <!-- Segunda linha de campos -->
    <div class="row align-items-end g-3 mt-0">
        <div class="col">
            <label for="dataPedido" class="form-label">Data do Pedido:</label>
            <input type="date" id="dataPedido" name="data_pedido" class="form-control" required>
        </div>

        <div class="col">
            <label for="quantidade" class="form-label">Quantidade de Frangos:</label>
            <input type="number" id="quantidade" name="quantidade" min="1" class="form-control" required>
        </div>

        <div class="col">
            <label for="total" class="form-label">Valor Total:</label>
            <input type="number" id="total" name="total" step="0.01" min="0.01" placeholder="R$" class="form-control" required>
        </div>
    </div>

    <!-- Botão para salvar o pedido -->
    <div class="text-center mt-4">
        <button type="submit" class="btn btn-primary w-50">Salvar Pedido</button>
    </div>
</form>

<hr>

<!-- Botões para alternar entre Pedidos  Pendentes, Finalizados e Cancelados -->
<ul class="nav nav-tabs justify-content-center mt-5 mb-3 gap-3" role="tablist">
    <li role="presentation">
        <a href="#pedidos-pendentes" class="active" id="abas-pedidos-pendentes" role="tab" data-bs-toggle="tab" aria-controls="pedidos-pendentes" aria-selected="true">
            <button type="button" class="btn btn-primary">Pedidos Pendentes</button>
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
    require_once __DIR__.'/../src/class/frango_assado/FrangoAssado.php';
    $frango_assado = new FrangoAssado();
?>

<div class="tab-content mb-5" id="conteudoAbasPedidos">
    <!-- Abas para Pedidos Pendentes -->
    <div class="tab-pane fade show active" id="pedidos-pendentes" role="tabpanel" aria-labelledby="abas-pedidos-pendentes">
        <h3 class="text-center my-4">Pedidos Pendentes</h3>
        <?php
        // Consulta para pedidos pendentes
        $template['status_pedido'] = 'Pendente';
        $template['acoes_pedido'] = true;
        $template['pedidos'] =  $frango_assado->listarPedidosPendentes(); // Método para listar pedidos pendentes
        // Inclui o template da tabela com os pedidos pendentes
        include __DIR__ . '/../src/template/admin/tabela_frango_assado_pedidos.php';
        ?>
    </div>

    <!-- Abas para Pedidos Finalizados -->
    <div class="tab-pane fade" id="pedidos-finalizados" role="tabpanel" aria-labelledby="abas-pedidos-finalizados">
        <h3 class="text-center my-4">Pedidos Finalizados</h3>
        <?php
        // Consulta para pedidos finalizados
		$template['status_pedido'] = 'Finalizado';
        $template['acoes_pedido'] = false;
        $template['pedidos'] =  $frango_assado->listarPedidosFinalizados(); // Método para listar pedidos finalizados
        // Inclui o template da tabela com os pedidos finalizados
        include __DIR__ . '/../src/template/admin/tabela_frango_assado_pedidos.php';
        ?>
    </div>

    <!-- Abas para Pedidos Cancelados -->
    <div class="tab-pane fade" id="pedidos-cancelados" role="tabpanel" aria-labelledby="abas-pedidos-cancelados">
        <h3 class="text-center my-4">Pedidos Cancelados</h3>
        <?php
        // Consulta para pedidos cancelados
		$template['status_pedido'] = 'Cancelado';
        $template['acoes_pedido'] = false;
        $template['pedidos'] =  $frango_assado->listarPedidosCancelados(); // Método para listar pedidos cancelados
        // Inclui o template da tabela com os pedidos cancelados
        include __DIR__ . '/../src/template/admin/tabela_frango_assado_pedidos.php';
        ?>
    </div>
</div>

<?php include __DIR__ . '/../src/template/admin/modal_detalhes_pedido.php'; ?>
<?php include __DIR__ . '/../src/template/admin/modal_lista_itens_pedido.php'; ?>
<?php include __DIR__ . '/../src/template/admin/footer.php'; ?>
