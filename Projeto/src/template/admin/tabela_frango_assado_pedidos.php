<!-- tabela_pedidos_frango_assado.php -->

<table class="table table-centered table-sm table-hover table-borderless">
    <?php $classes = ['Cancelado' => 'table-danger', 'Finalizado' => 'table-success', 'Pendente' => 'table-secondary']; ?>
    <thead class="<?= $classes[$template['status_pedido']] ?? '' ?>">
        <tr>
            <th scope="col">N° Pedido</th>
            <th scope="col">Nome</th>
            <th scope="col">Telefone</th>
            <th scope="col">Quantidade</th>
            <th scope="col">Valor Total</th>
            <th scope="col">Data do Pedido</th>
            <th scope="col">Obs.</th>
            <?php if ($template['acoes_pedido']): ?>
                <th scope="col" style="width: 290px">Ações</th>
            <?php endif; ?>
        </tr>
    </thead>
    <?php $classes = ['Cancelado' => 'table-danger', 'Finalizado' => 'table-success']; ?>
    <tbody class="<?= $classes[$template['status_pedido']] ?? '' ?>">
        <?php foreach ($template['pedidos'] as $item): ?>
            <tr data-id-pedido="<?= htmlspecialchars($item['IdPedido']); ?>">
                <td><?= htmlspecialchars($item['IdPedido']); ?></td>
                <td><?= htmlspecialchars($item['Nome']); ?></td>
                <td><?= htmlspecialchars($item['Telefone']); ?></td>
                <td><?= htmlspecialchars($item['Quantidade']); ?></td>
                <td>R$ <?= number_format($item['Total'], 2, ',', '.'); ?></td>
                <td><?= date('d/m/Y', strtotime(datetime: $item['DataPedido'])); ?></td>
                <td class="text-truncate"><?= htmlspecialchars($item['Observacoes']); ?></td>
                <?php if ($template['acoes_pedido']): ?>
                    <td>
                        <button type="button" class="btn btn-sm btn-warning" onclick="editarPedido(event)" 
                            data-id="<?= $item['IdPedido'] ?>" 
                            data-nome="<?= htmlspecialchars($item['Nome']); ?>" 
                            data-data-pedido="<?= htmlspecialchars($item['DataPedido']); ?>"
                            data-telefone="<?= htmlspecialchars($item['Telefone']); ?>" 
                            data-quantidade="<?= htmlspecialchars($item['Quantidade']); ?>" 
                            data-total="<?= htmlspecialchars($item['Total']); ?>" 
                            data-observacoes="<?= htmlspecialchars($item['Observacoes']); ?>"
                            data-status="<?= htmlspecialchars($item['Status']); ?>">
                            <i class="bi bi-pencil-fill me-1"></i> Editar
                        </button>
                        <button type="button" class="btn btn-success btn-sm" onclick="finalizarPedido(this, '<?= $item['IdPedido'] ?>')">
                            <i class="bi bi-check-circle-fill me-1"></i> Finalizar
                        </button>
                        <button type="button" class="btn btn-sm btn-danger" onclick="cancelarPedido(this, '<?= $item['IdPedido'] ?>')">
                            <i class="bi bi-trash-fill me-1"></i> Cancelar
                        </button>
                    </td>
                <?php endif; ?>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
