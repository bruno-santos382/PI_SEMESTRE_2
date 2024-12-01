<!-- tabela_pedidos.php -->

<table class="table table-centered table-sm table-hover table-borderless">
    <?php $classes = ['Cancelado' => 'table-danger', 'Finalizado' => 'table-success', 'Em Andamento' => 'table-secondary']; ?>
    <thead class="<?= $classes[$template['status_pedido']] ?? '' ?>">
        <tr>
            <th scope="col">N° Pedido</th>
            <th scope="col">Cliente</th>
            <th scope="col">Data Pedido</th>
            <th scope="col">Data Retirada</th>
            <th scope="col">Produtos</th>
            <th scope="col">Valor Total</th>

            <?php if ($template['acoes_pedido']): ?>
                <th scope="col" style="width: 290px">Ações</th>
            <?php endif; ?>
        </tr>
    </thead>
    <?php $classes = ['Cancelado' => 'table-danger', 'Finalizado' => 'table-success']; ?>
    <tbody class="<?= $classes[$template['status_pedido']] ?? '' ?>">
        <?php foreach ($template['pedidos'] as $item): ?>
            <tr data-id-pedido="<?= $item['IdPedido'] ?>">
                <td><?php echo htmlspecialchars($item['IdPedido']); ?></td>
                <td><?php echo htmlspecialchars($item['ClienteNome']); ?></td>
                <td><?php echo date('d/m/Y', strtotime($item['DataPedido'])); ?></td>
                <td>
                    <?php 
                    if (!empty($item['DataRetirada'])) {
                        echo date('d/m/Y', strtotime($item['DataRetirada']));
                    } else {
                        echo '<b class="text-danger">(Não informada)</b>';
                    }
                    ?>
                </td>
                <td>
                    <button type="button" class="btn btn-link" 
                            data-bs-toggle="modal" 
                            data-bs-target="#modalItensPedido" 
                            data-id="<?= $item['IdPedido'] ?>" 
                            onclick="verItensPedido(event)">
                        Ver itens
                    </button>
                </td>
                <td>
                    <?php echo 'R$ ' . number_format($item['ValorTotal'], 2, ',', '.'); ?>
                </td>
                <?php if ($template['acoes_pedido']): ?>
                    <td>
                        <button type="button" class="btn btn-sm btn-warning" onclick="editarPedido(event)" 
                            data-id="<?= $item['IdPedido'] ?>" 
                            data-id-cliente="<?= $item['IdCliente'] ?>" 
                            data-data-pedido="<?= $item['DataPedido'] ?>" 
                            data-data-retirada="<?= $item['DataRetirada'] ?>"
                            data-valor-total="<?= $item['ValorTotal'] ?>" 
                            data-status="<?= $item['Status'] ?>">
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
