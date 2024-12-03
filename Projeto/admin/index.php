<?php 

$template = array(
    'titulo' =>  'Painel Administrativo &mdash; GM Supermercado',
    'scripts' => ['./static/js/admin/main.js']
);

include __DIR__ . '/../src/template/admin/header.php';
?>
       
<h1 class="text-center my-4">Bem-vindo ao Painel Administrativo</h1>
<p class="lead text-center mb-5">Aqui você pode gerenciar usuários, produtos e pedidos.</p>

<div class="row justify-content-center row-cols-1 row-cols-md-2 g-4">
    <!-- Usuários -->
    <div class="col">
        <div class="card shadow-sm border-light d-flex flex-column">
            <div class="card-header bg-primary text-white d-flex align-items-center">
                <i class="bi bi-person-lines-fill me-2"></i>
                <strong>Gerenciar Usuários</strong>
            </div>
            <div class="card-body flex-grow-1">
                <p>Cadastre e gerencie os usuários do sistema. Veja as permissões de cada um e altere quando necessário.</p>
            </div>
            <div class="card-footer bg-light">
                <div class="justify-content-between gap-5 d-flex" role="group" aria-label="Ações de Gerenciamento de Usuários">
                    <a href="admin/gerenciar_usuarios.php" class="btn btn-primary mb-2" style="flex: 1 1 0">
                        <i class="bi bi-person-check me-1"></i> Gerenciar Usuários
                    </a>
                    <a href="admin/cadastro_usuario.php" class="btn btn-primary mb-2" style="flex: 1 1 0">
                        <i class="bi bi-person-plus me-1"></i> Cadastrar Usuário
                    </a>
                </div>
                <hr>
                <div class="justify-content-between gap-5 d-flex" role="group" aria-label="Ações Relacionadas ao Gerenciamento">
                    <a href="admin/gerenciar_clientes.php" class="btn btn-primary mb-2" style="flex: 1 1 0">
                        <i class="bi bi-person-fill me-1"></i> Gerenciar Clientes
                    </a>
                    <a href="admin/gerenciar_funcionarios.php" class="btn btn-primary mb-2" style="flex: 1 1 0">
                        <i class="bi bi-person-badge me-1"></i> Gerenciar Funcionários
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Produtos -->
    <div class="col">
        <div class="card shadow-sm border-light d-flex flex-column">
            <div class="card-header bg-success text-white d-flex align-items-center">
                <i class="bi bi-box-seam me-2"></i>
                <strong>Gerenciar Produtos</strong>
            </div>
            <div class="card-body flex-grow-1">
                <p>Cadastre e gerencie os produtos disponíveis na loja. Inclua promoções e categorias de produtos.</p>
            </div>
            <div class="card-footer bg-light">
                <div class="justify-content-between gap-5 d-flex" role="group" aria-label="Ações de Gerenciamento de Produtos">
                    <a href="admin/gerenciar_produtos.php" class="btn btn-success mb-2" style="flex: 1 1 0">
                        <i class="bi bi-box-arrow-up me-1"></i> Gerenciar Produtos
                    </a>
                    <a href="admin/cadastro_produto.php" class="btn btn-success mb-2" style="flex: 1 1 0">
                        <i class="bi bi-plus-circle me-1"></i> Cadastrar Produto
                    </a>
                </div>
                <hr>
                <div class="justify-content-between gap-5 d-flex" role="group" aria-label="Ações de Promoções e Categorias">
                    <a href="admin/promocoes.php" class="btn btn-success mb-2" style="flex: 1 1 0">
                        <i class="bi bi-percent me-1"></i> Gerenciar Promoções
                    </a>
                    <a href="admin/categorias.php" class="btn btn-success mb-2" style="flex: 1 1 0">
                        <i class="bi bi-tags me-1"></i> Gerenciar Categorias
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Pedidos -->
    <div class="col">
        <div class="card shadow-sm border-light d-flex flex-column">
            <div class="card-header bg-warning text-dark d-flex align-items-center">
                <i class="bi bi-cart-check me-2"></i>
                <strong>Gerenciar Pedidos</strong>
            </div>
            <div class="card-body flex-grow-1">
                <p>Visualize e gerencie os pedidos realizados. Altere o status conforme necessário. Inclui "Frango Assado" como produto popular.</p>
            </div>
            <div class="card-footer bg-light">
                <div class="justify-content-between gap-5 d-flex" role="group" aria-label="Ações de Gerenciamento de Pedidos">
                    <a href="admin/pedidos.php" class="btn btn-warning mb-2" style="flex: 1 1 0">
                        <i class="bi bi-cart-plus me-1"></i> Gerenciar Pedido
                    </a>
                    <a href="admin/frango_assado.php" class="btn btn-warning mb-2" style="flex: 1 1 0">
                        <i class="bi bi-cart-fill me-1"></i> Frango Assado
                    </a>
                    <!-- Optional link for popular product -->
                    <!-- Uncomment if needed -->
                    <!-- 
                    <a href="produto_frango_assado.php" class="btn btn-warning mb-2" style="flex: 1 1 0"> 
                        <i class="bi bi-cup-straw me-1"></i> Frango Assado 
                    </a>
                    -->
                </div>
                <hr />
            </div>
        </div>
    </div>

</div> <!-- End of row -->

<?php include __DIR__ . '/../src/template/admin/footer.php'; ?>
