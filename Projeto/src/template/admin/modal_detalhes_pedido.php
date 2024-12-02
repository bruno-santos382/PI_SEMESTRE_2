<!-- Modal -->
<div class="modal fade" id="modalDetalhesPedido" tabindex="-1" aria-labelledby="modalDetalhesPedidoLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalDetalhesPedidoLabel">Detalhes do Pedido</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
            </div>
            <div class="modal-body">
                <p><strong>N° Pedido:</strong> <span id="detalhePedidoId"></span></p>
                <p><strong>Data do Pedido:</strong> <span id="detalheDataPedido"></span></p>
                <p><strong>Data de Entrega Solicitada:</strong> <span id="detalheDataAgendada"></span></p>
                <p><strong>Endereço de Entrega:</strong> <span id="detalheEnderecoEntrega"></span></p>
                <p><strong>Método de Pagamento:</strong> <span id="detalheMetodoPagamento"></span></p>
                <p><strong>Valor Total:</strong> R$ <span id="detalheValorTotal"></span></p>
                <p><strong>Status:</strong> <span id="detalheStatusPedido"></span></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>
