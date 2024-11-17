
<!-- Notificar que produto foi adicionado ao carrinho -->
<div class="modal fade" id="modalAdicionarProduto" tabindex="-1" aria-labelledby="modalAdicionarProdutoLabel" role="dialog">
    <div class="modal-dialog  modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body text-center">
                <div id="checkIcon">
                    <i class="text-success fa-solid fa-circle-check" style="font-size: 64px;"></i>
                </div>
                <div class="mt-4 py-2">
                    <h5 class="h5" id="modalAdicionarProdutoLabel">Produto Adicionado com Sucesso!</h5>
                    <p class="text-secondary">O produto foi adicionado ao seu carrinho.</p>
                </div>
                <div class="py-1">
                    <button type="button" class="btn btn-sm btn-outline-success rounded-pill px-5" data-bs-dismiss="modal">OK</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Notificar que ocorreu um erro ao adicionar o produto ao carrinho -->
<div class="modal fade" id="modalErroAdicionarProduto" tabindex="-1" aria-labelledby="modalErroAdicionarProdutoLabel" role="dialog">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body text-center">
                <div id="errorIcon">
                    <i class="text-danger fa-solid fa-circle-xmark" style="font-size: 64px;"></i>
                </div>
                <div class="mt-4 py-2">
                    <h5 class="h5" id="modalErroAdicionarProdutoLabel">Erro ao Adicionar Produto!</h5>
                    <p class="text-secondary">Ocorreu um erro ao tentar adicionar o produto ao seu carrinho. Tente novamente.</p>
                </div>
                <div class="py-1">
                    <button type="button" class="btn btn-sm btn-outline-danger rounded-pill px-5" data-bs-dismiss="modal">Fechar</button>
                </div>
            </div>
        </div>
    </div>
</div>