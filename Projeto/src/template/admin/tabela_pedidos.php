<!-- tabela_pedidos.php -->

<table class="table table-centered table-sm table-hover table-borderless">
    <?php $classes = ['Cancelado' => 'table-danger', 'Finalizado' => 'table-success', 'Em Andamento' => 'table-secondary']; ?>
    <thead class="<?= $classes[$template['status_pedido']] ?? '' ?>">
        <tr>
            <th scope="col">N° Pedido</th>
            <th scope="col">Cliente</th>
            <th scope="col">Data de Entrega Solicitada</th>
            <th scope="col">Data Retirada</th>
            <th scope="col">Valor Total</th>
            <th scope="col">Detalhes</th>
            <th scope="col">Produtos</th>

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
                <td><?= empty($item['DataAgendada']) ? '<i class="text-muted">(Retirada no mercado)</i>' : date('d/m/Y', strtotime($item['DataAgendada'])); ?></td>
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
                    <?php echo 'R$ ' . number_format($item['ValorTotal'], 2, ',', '.'); ?>
                </td>
                <td>
                    <button type="button" class="btn btn-link" 
                            data-bs-target="#modalDetalhesPedido" 
                            data-bs-toggle="modal"
                            data-id-pedido="<?= htmlspecialchars($item['IdPedido'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                            data-data-pedido="<?= htmlspecialchars($item['DataPedido'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                            data-data-agendada="<?= htmlspecialchars($item['DataAgendada'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                            data-endereco-entrega="<?= htmlspecialchars($item['EnderecoEntrega'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                            data-metodo-pagamento="<?= htmlspecialchars($item['MetodoPagamento'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                            data-valor-total="<?= htmlspecialchars($item['ValorTotal'] ?? '0.00', ENT_QUOTES, 'UTF-8') ?>"
                            data-status="<?= htmlspecialchars($item['Status'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                            onclick="verDetalhesPedido(event)">
                        Ver detalhes
                    </button>
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
