<!-- promocoes_tabela.php -->

<table class="table table-centered table-sm table-hover table-borderless ">
	<thead class="<?= $template['promocoes_expiradas'] ? 'table-danger' : 'table-secondary'; ?> ">
		<tr>
			<th scope="col">Produto</th>
			<th scope="col">Imagem</th>
			<th scope="col">Data Início</th>
			<th scope="col">Data Fim</th>
			<th scope="col">Desconto (%)</th>
			<th scope="col">Preço Antigo</th>
			<th scope="col">Preço com Desconto</th>
			<th scope="col">Ações</th>
		</tr>
	</thead>
	<tbody class="<?php if ($template['promocoes_expiradas']) echo 'table-danger'; ?>">
		<?php foreach ($template['promocoes'] as $item): ?>
			<tr data-id-promocao="<?= $item['IdPromocao'] ?>">
				<td><?php echo htmlspecialchars($item['Nome']); ?></td>
				<td>
					<img src="<?= $item['Imagem'] ?? 'static/img/galeria.png' ?>" alt="<?= htmlspecialchars($item['Nome']) ?>"
						class="img-fluid" style="max-width: 50px; height: auto;">
				</td>
				<td><?php echo date('d/m/Y', strtotime($item['DataInicio'])); ?></td>
				<td><?php echo date('d/m/Y', strtotime($item['DataFim'])); ?></td>
				<td><?php echo htmlspecialchars($item['Desconto']); ?>%</td>
				<td class="text-danger fw-bold">
					<?php echo 'R$ ' . number_format($item['PrecoAntigo'], 2, ',', '.'); ?>
				</td>
				<td class="text-success fw-bold">
					<?php echo 'R$ ' . number_format($item['PrecoComDesconto'], 2, ',', '.'); ?>
				</td>
				<td>
					<button type="button"
						class="btn btn-sm btn-warning" 
						onclick="editarPromocao(event)" 
						data-id="<?= $item['IdPromocao'] ?>" 
						data-id-produto="<?= $item['IdProduto'] ?>" 
						data-data-inicio="<?= $item['DataInicio'] ?>" 
						data-data-fim="<?= $item['DataFim'] ?>"
						data-desconto="<?= $item['Desconto'] ?>">
						<i class="bi bi-pencil-fill me-1"></i> Editar
					</button>
					<button type="button"  class="btn btn-sm btn-danger" onclick="removerPromocao(this, '<?= $item['IdPromocao'] ?>')">
						<i class="bi bi-trash-fill me-1"></i> Excluir
					</button>
				</td>
			</tr>
		<?php endforeach; ?>
	</tbody>
</table>