<?php 

$template = array(
    'titulo' =>  'Categorias &mdash; GM Supermercado',
    'menu_atual' => 'categorias',
    'scripts' => ['./static/js/admin/categorias.js']
);

include __DIR__ . '/../src/template/admin/header.php';
?>


<?php
    $paginas = [
        'hortifruti' => 'Hortifruti',
        'acougue' => 'Açougue',
        'mercenaria' => 'Mercenaria',
        'bebidas' => 'Bebidas',
        'padaria' => 'Padaria',
        'limpeza' => 'Limpeza'
    ];
?>

<h2 class="text-center my-4">Categorias</h2>

<div class="container w-75 mx-auto">
    <div id="alertaCategoria" class="mb-3">
        <?php include __DIR__. '/../src/template/alertas.php'; ?>
    </div>

    <div id="alertaEditando" class="slide-down mb-2">
        <span class="text-success">
            <i class="bi bi-pencil-square"></i> Editando categoria <strong id="categoriaSelecionada"></strong>
        </span>
    </div>

    <form action="" method="POST" class="mb-4" id="formCadastro">
        <div class="row align-items-end g-3">
            <input type="number" name="id" id="codigo" hidden>

            <!-- Nome da Categoria -->
            <div class="col">
                <label for="nome" class="form-label fw-bold text-muted">Categoria:</label>
                <input type="text" class="form-control" id="nome" name="nome" placeholder="Digite o nome da categoria" required>
            </div>

            <!-- Incluir na página -->
            <div class="col">
                <label for="pagina" class="form-label fw-bold text-muted">Página (opcional):</label>
                <select name="pagina" id="pagina" class="form-select">
                    <option value="" disabled selected>Escolha a página da categoria</option>
                    <?php foreach ($paginas as $chave => $descricao): ?>
                        <option value="<?= $chave ?>"><?= $descricao ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Botão de Cadastrar -->
            <div class="col-auto">
                <button type="submit" class="btn btn-primary">Cadastrar</button>
            </div>
        </div>
    </form>

    <hr>

    <table class="table table-centered table-hover table-sm table-borderless">
        <thead class="table-secondary">
            <tr>
                <th scope="col" style="width: 50px;">Código</th>
                <th scope="col">Categoria</th>
                <th scope="col">Página</th>
                <th scope="col" style="width: 200px;">Ações</th>
            </tr>
        </thead>
        <tbody id="dadosTabela">
            <?php 
                require_once __DIR__.'/../src/class/categoria/Categoria.php';
                $categoria = new Categoria();
            ?>
            <?php foreach ($categoria->listarTudo() as $item): ?>
                <tr data-id-categoria="<?= $item['IdCategoria'] ?>">
                    <td><?= $item['IdCategoria'] ?></td>
                    <td><?= htmlspecialchars($item['Nome']) ?></td>
                    <td><?= $paginas[$item['Pagina']] ?? '<i class="text-muted">(Nenhuma)</i>' ?></td>
                    <td>
                        <button type="button" class="btn btn-warning btn-sm" title="Editar" 
                                onclick="editarCategoria('<?= $item['IdCategoria'] ?>', '<?= $item['Nome'] ?>', '<?= $item['Pagina'] ?>')">
                            <i class="bi bi-pencil me-1"></i> Editar
                        </button>
                        <button type="button" class="btn btn-danger btn-sm" onclick="removerCategoria(this, '<?= $item['IdCategoria'] ?>')" title="Remover">
                            <i class="bi bi-trash me-1"></i> Remover
                        </button>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

</div>

<?php include __DIR__ . '/../src/template/admin/footer.php'; ?>