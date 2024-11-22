<?php 

$template = array(
    'titulo' =>  'Categorias &mdash; GM Supermercado',
    'menu_atual' => 'categorias',
    'scripts' => ['./static/js/admin/categorias.js']
);

include __DIR__ . '/../src/template/admin/header.php';
?>

<h2 class="text-center my-4">Gerenciar Promoções</h2>

<!-- Formulário para gerenciar promoções -->
<form id="formPromocao" action="#" method="POST" class="mb-4">
    <div class="row align-items-end g-3">
        <div class="col">
            <label for="produtoSelect" class="form-label">Selecionar Produto:</label>
            <select id="produtoSelect" name="produto_id" class="form-select" required>
                <!-- Exemplo de opções, você deve preencher com produtos do seu banco de dados -->
                <option value="">Selecione um produto</option>
                <!-- Aqui você deve adicionar opções dinâmicas com produtos do banco de dados -->
                <option value="1">Abacaxi</option>
                <!-- Adicione mais produtos aqui -->
            </select>
        </div>

        <div class="col">
            <label for="dataInicio" class="form-label">Data de Início:</label>
            <input type="date" id="dataInicio" name="data_inicio" class="form-control" required>
        </div>

        <div class="col">
            <label for="dataFim" class="form-label">Data de Fim:</label>
            <input type="date" id="dataFim" name="data_fim" class="form-control" required>
        </div>

        <div class="col">
            <label for="desconto" class="form-label">Desconto:</label>
            <input type='number' id='desconto' name='desconto' step='.01' min='.01' max="100" placeholder='%' 
                    class='form-control' required>
        </div>

        <!-- Botão para salvar a promoção -->
        <button type='submit' class='btn btn-primary'>Salvar Promoção</button>
    </div>
</form>

<!-- Tabela para listar promoções existentes (opcional) -->
<!-- Você pode adicionar uma tabela aqui para mostrar promoções já cadastradas -->


<?php include __DIR__ . '/../src/template/admin/footer.php'; ?>