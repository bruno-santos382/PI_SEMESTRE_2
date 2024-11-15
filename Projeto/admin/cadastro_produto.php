<?php 

$template = array(
    'titulo' =>  'Cadastro de Produtos &mdash; GM Supermercado',
    'menu_atual' => 'cadastro_produto',
    'scripts' => ['./static/js/admin/cadastro_produto.js']
);

include __DIR__ . '/../src/template/admin/header.php';
?>
<h2 class="text-center my-4">Cadastro de Produto</h2>

<div class="w-50 mx-auto">
    <div id="alertaProduto">
        <?php include __DIR__. '/../src/template/alertas.php'; ?>   
    </div>

    <form id="formCadastro" class="mb-3" action="" method="POST">
            <!-- Nome do Produto -->
            <div class="mb-3">
                <label for="nome" class="form-label fw-bold text-muted">Nome do Produto:</label>
                <input type="text" class="form-control" id="nome" name="nome" placeholder="Digite o nome do produto" required>
            </div>
            <!-- Marca -->
            <div class="mb-3">
                <label for="marca" class="form-label fw-bold text-muted">Marca:</label>
                <input type="text" class="form-control" id="marca" name="marca" placeholder="Digite a marca do produto" required>
            </div>
            <!-- Preço -->
            <div class="mb-3">
                <label for="preco" class="form-label fw-bold text-muted">Preço:</label>
                <input type="number" class="form-control" id="preco" name="preco" placeholder="R$" required>
            </div>
            <!-- Quantidade em Estoque -->
            <div class="mb-3">
                <label for="estoque" class="form-label fw-bold text-muted">Quantidade em Estoque:</label>
                <input type="number" class="form-control" id="estoque" name="estoque" placeholder="Digite a quantidade em estoque" required>
            </div>

            <!-- Imagem do Produto -->
            <label class="form-label fw-bold text-muted">Imagem do Produto:</label>
            <div class="card mb-3">
                <div class="card-body d-flex flex-column align-items-center">
                    <img id="imagemProduto" src="static/img/galeria.png" data-default-src="static/img/galeria.png" width="150" alt="Prévia da imagem" class="img-fluid mb-3">
                    <input id="idImagem" name="id_imagem" type="number" value="" hidden>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalImagens">
                        Selecionar Imagem
                    </button>
                </div>
            </div>

            <!-- Botão de Cadastrar -->
            <div class="d-grid">
                <button type="submit" class="btn btn-primary btn-lg">Cadastrar</button>
            </div>
    </form>

</div>

<!-- Galeria de Imagens -->
<div class="modal fade" id="modalImagens" tabindex="-1" aria-labelledby="modalImagensLabel" aria-hidden="true">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="modalImagensLabel">Galeria de Imagens</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
			</div>
			<div class="modal-body">
                <div id="alertaImagem">
                    <?php include __DIR__. '/../src/template/alertas.php'; ?>   
                </div>

				<!-- Tabs -->
				<ul class="nav nav-tabs" id="tabImagens" role="tablist">
					<li class="nav-item" role="presentation">
						<button class="nav-link active" id="tab-visualizar" data-bs-toggle="tab"
							data-bs-target="#tabContentVisualizar" type="button" role="tab"
							aria-controls="tabContentVisualizar" aria-selected="true">Visualizar Imagens</button>
					</li>
					<li class="nav-item" role="presentation">
						<button class="nav-link" id="tab-upload" data-bs-toggle="tab" data-bs-target="#tabContentUpload"
							type="button" role="tab" aria-controls="tabContentUpload" aria-selected="false">Nova
							Imagem</button>
					</li>
				</ul>

				<!-- Tab Content -->
				<div class="tab-content mt-3" id="tabImagensContent">
                    

					<!-- Visualizar Imagens -->
					<div class="tab-pane fade show active" id="tabContentVisualizar" role="tabpanel" aria-labelledby="tab-visualizar">

                        <div class="text-center mb-2">
                            <small class="text-muted">Clique em uma imagem para selecioná-la</small>
                        </div>

						<div id="galeriaImagens" class="d-grid gap-2 justify-content-center" style="grid-template-columns: repeat(auto-fill, 150px);">
                            <?php 
                                require __DIR__.'/../src/class/imagem/Imagem.php';
                                $imagem = new Imagem(); 
                            ?>
                            <?php foreach ($imagem->lista() as $img): ?>
                                <div class="card img-card" data-caminho-imagem="<?= $img['Caminho'] ?>" data-id-imagem="<?= $img['IdImagem'] ?>" title="<?= $img['NomeImagem'] ?>">
                                    <div class="card-header text-center bg-light">
                                        <div class="text-muted small text-truncate"><?= htmlspecialchars($img['NomeImagem']) ?></div>
                                    </div>
                                    <div class="card-body d-flex flex-column justify-content-between">
                                        <img 
                                            src="<?=$img['Caminho'] ?>" 
                                            alt="<?= htmlspecialchars($img['NomeImagem']) ?>" 
                                            class="card-img-top mx-auto" 
                                            style="width: 100px;" >
                                        <button 
                                            type="button" 
                                            class="btn btn-danger btn-sm btn-excluir-img" 
                                            data-id-imagem="<?= $img['IdImagem'] ?>" >
                                            Remover
                                        </button>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

					<!-- Upload de Nova Imagem -->
					<div class="tab-pane fade" id="tabContentUpload" role="tabpanel" aria-labelledby="tab-upload">
						<form id="formUploadImagem" action="" method="POST">
							<div class="mb-3">
								<label for="uploadComputador" class="form-label">Carregar do Computador</label>
								<input name="arquivo" type="file" class="form-control" id="uploadComputador" accept="image/*">
							</div>
							<div class="mb-3">
								<label for="uploadLink" class="form-label">Ou adicionar por URL</label>
								<input name="url" type="url" class="form-control" id="uploadLink"
									placeholder="https://exemplo.com/imagem.jpg">
							</div>
							<button type="submit" class="btn btn-primary">Enviar</button>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<?php include __DIR__ . '/../src/template/admin/footer.php'; ?>