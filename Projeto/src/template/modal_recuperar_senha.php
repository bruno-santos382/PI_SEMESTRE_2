

<!-- Modal Recuperar Senha -->
<div class="modal fade" id="modalRecuperarSenha" tabindex="-1" aria-labelledby="modalRecuperarSenhaLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalRecuperarSenhaLabel">Recuperação de Senha</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
            </div>
            <div class="modal-body">
                <div id="alertaRecuperarSenha" class="mb-3">
                    <?php include __DIR__ . '/alertas.php' ?>
                </div>
                <form id="recuperarSenhaForm" action="#" method="POST">
                    <div class="form-group mb-3">
                        <label for="emailRecuperacao">E-mail</label>
                        <input type="email" name="email" class="form-control" id="emailRecuperacao" placeholder="Digite seu e-mail" required>
                    </div>
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">Recuperar Senha</button>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>