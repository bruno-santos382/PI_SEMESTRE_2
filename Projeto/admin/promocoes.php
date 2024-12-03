<?php

$template = array(
	'titulo' => 'Promoções &mdash; GM Supermercado',
	'menu_atual' => 'promocoes',
	'scripts' => ['./static/js/admin/promocoes.js'],
	'styles' => ['./static/css/admin/promocoes.css']
);

include __DIR__ . '/../src/template/admin/header.php';
?>

<h2 class="text-center my-4">Gerenciar Promoções</h2>

<div id="alertaPromocao" class="mb-3">
	<?php include __DIR__. '/../src/template/alertas.php'; ?>
</div>

<div id="alertaEditando" class="slide-down mb-2">
	<span class="text-success">
		<i class="bi bi-pencil-fill-square"></i> Editando promoção do produto <strong id="produtoSelecionado"></strong>
	</span>
</div>

<!-- Formulário para gerenciar promoções -->
<form id="formCadastro" action="#" method="POST" class="mb-5">
	<input type="number" name="id" id="codigo" hidden value="">

	<?php
		require_once __DIR__.'/../src/class/produto/Produto.php';
		$produto = new Produto();
	?>

	<div class="row align-items-end g-3">
		<div class="col">
			<label for="produto" class="form-label">Selecionar Produto:</label>
			<select id="produto" name="produto" class="form-select" required>
				<option value="">Selecione um produto</option>

				<?php foreach ($produto->listarTudo() as $item): ?>
					<option value="<?= $item['IdProduto'] ?>"><?= $item['Nome'] ?></option>
				<?php endforeach; ?>
			</select>
		</div>

		<div class="col">
			<label for="dataInicio" class="form-label">Data de Início:</label>
			<input type="date" id="dataInicio" name="data_inicio" class="form-control" required>
		</div>

		<div class="col">
			<label for="dataFim" class="form-label">Data de Fim:</label>
			<input type="date" id="dataFim" name="data_fim" class="form-control" required>
		</div>

		<div class="col">
			<label for="desconto" class="form-label">Desconto:</label>
			<input type='number' id='desconto' name='desconto' step='.01' min='.01' max="100" placeholder='%'
				class='form-control' required>
		</div>

		<!-- Botão para salvar a promoção -->
		<div class="text-center">
			<button type='submit' class="btn btn-primary w-50">Salvar Promoção</button>
		</div>
	</div>
</form>

<hr>

<!-- Botões para alternar entre Promoções Ativas e Expiradas -->
<ul class="nav nav-tabs justify-content-center my-5 mb-3 gap-3" role="tablist">
    <li  role="presentation">
        <a href="#promocoes-ativas" class="active" id="abas-promocoes-ativas" role="tab" data-bs-toggle="tab" aria-controls="promocoes-ativas" aria-selected="true">
            <button type="button" class="btn btn-primary">Promoções Ativas</button>
        </a>
    </li>
    <li role="presentation">
        <a href="#promocoes-expiradas" class="" id="abas-promocoes-expiradas" role="tab" data-bs-toggle="tab" aria-controls="promocoes-expiradas" aria-selected="false">
            <button type="button" class="btn btn-secondary">Promoções Expiradas</button>
        </a>
    </li>
</ul>

<?php
    require_once __DIR__.'/../src/class/promocao/Promocao.php';
    $promocao = new Promocao();
?>

<div class="tab-content" id="conteudoAbasPromocoes">
    <!-- Abas para Promoções Ativas -->
    <div class="tab-pane fade show active" id="promocoes-ativas" role="tabpanel" aria-labelledby="abas-promocoes-ativas">
        <h3 class="text-center my-4">Promoções Ativas</h3>
        <?php
        // Consulta para promoções ativas
		$template['promocoes_expiradas'] = false;
		$template['promocoes'] =  $promocao->listarPromocoesAtivas(); // Método para listar promoções ativas
        // Inclui o template da tabela com as promoções ativas
        include __DIR__ . '/../src/template/admin/tabela_promocoes.php';
        ?>
    </div>

    <!-- Abas para Promoções Expiradas -->
    <div class="tab-pane fade" id="promocoes-expiradas" role="tabpanel" aria-labelledby="abas-promocoes-expiradas">
        <h3 class="text-center my-4">Promoções Expiradas</h3>
        <?php
        // Consulta para promoções expiradas
		$template['promocoes_expiradas'] = true;
		$template['promocoes'] =  $promocao->listarPromocoesExpiradas(); // Método para listar promoções expiradas
        // Inclui o template da tabela com as promoções expiradas
        include __DIR__ . '/../src/template/admin/tabela_promocoes.php';
        ?>
    </div>
</div>

<?php include __DIR__ . '/../src/template/admin/footer.php'; ?>