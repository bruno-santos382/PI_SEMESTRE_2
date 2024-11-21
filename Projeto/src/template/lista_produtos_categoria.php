
<?php

// Inicializa o ID interno da categoria para a tag HTML, caso ainda não tenha sido definido
if (!isset($_id_categoria)) {
    $_id_categoria = 0; // Define o valor inicial como 0, se a variável não existir
}

// Incrementa o valor do ID interno a cada inclusão do template
$_id_categoria++;

?>

<h3 class="mb-3 text-capitalize"><?= $titulo_secao ?? $nome_categoria ?></h3>
<div class="position-relative">
    <button class="scroll-button scroll-left" onclick="scrollContainer('<?= 'categoria'.$_id_categoria ?>', -1)">&#10094;</button>
    <div class="scroll-container" id="<?= 'categoria'.$_id_categoria ?>">
        <?php $produtos = $produto->listarPorCategoria($nome_categoria); ?>
        
        <div class="card card-nenhum-resultado w-100" style="<?php if (!empty($produtos)) echo 'display: none'; ?>">
            <div class="card-body text-center">
                <h5>Nenhum Resultado Encontrado</h5>
                <p>Desculpe, mas não encontramos produtos que correspondam à sua busca.</p>
            </div>
        </div>

        <?php foreach ($produto->listarPorCategoria($nome_categoria) as $item): ?>
            <div class="card card-produto">
                <img src="<?= $item['Imagem'] ?>" class="card-img-top" alt="<?= $item['Nome'] ?>">
                <div class="card-body text-center">
                    <h5 class="card-title"><?= $item['Nome'] ?></h5>
                    <p class="card-text"><?= $item['Marca'] ?></p>
                    <p class="text-danger"><strong>R$ <?= number_format($item['Preco'], 2, ',', '.') ?></strong></p>
                    <button type="button" data-id-produto="<?= $item['IdProduto'] ?>" class="btn btn-success btn-adicionar-produto">Adicionar ao Carrinho</button>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    <button class="scroll-button scroll-right" onclick="scrollContainer('<?= 'categoria'.$_id_categoria ?>', 1)">&#10095;</button>
</div>