<?php 

$template = array(
    'titulo' =>  'Painel Administrativo &mdash; GM Supermercado',
    'scripts' => ['./static/js/admin/main.js']
);

include __DIR__ . '/../src/template/admin/header.php';
?>
       

<h1 class="text-center my-4">Bem-vindo ao Painel Administrativo</h1>
<p class="lead text-center mb-5">Aqui você pode gerenciar usuários, produtos e acessar as configurações do sistema.</p>

<div class="row">
    <!-- Usuários -->
    <div class="col-md-6 mb-4">
        <div class="card shadow-sm border-light">
            <div class="card-header bg-primary text-white d-flex align-items-center">
                <i class="bi bi-person-lines-fill me-2"></i> 
                <strong>Gerenciar Usuários</strong>
            </div>
            <div class="card-body">
                <p>Cadastre e gerencie os usuários do sistema. Veja as permissões de cada um e altere quando necessário.</p>
                <div class="d-flex justify-content-between">
                    <a href="usuarios/gerenciar.php" class="btn btn-primary">Gerenciar Usuários</a>
                    <a href="usuarios/cadastrar.php" class="btn btn-outline-secondary">Cadastrar Usuário</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Produtos -->
    <div class="col-md-6 mb-4">
        <div class="card shadow-sm border-light">
            <div class="card-header bg-success text-white d-flex align-items-center">
                <i class="bi bi-box-seam me-2"></i> 
                <strong>Gerenciar Produtos</strong>
            </div>
            <div class="card-body">
                <p>Cadastre e gerencie os produtos disponíveis na loja.</p>
                <div class="d-flex justify-content-between">
                    <a href="produtos/gerenciar.php" class="btn btn-success">Gerenciar Produtos</a>
                    <a href="produtos/cadastrar.php" class="btn btn-outline-secondary">Cadastrar Produto</a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Estatísticas (Resumo de atividades) -->
<div class="row">
    <div class="col-md-12 mb-4">
        <div class="card shadow-sm border-light">
            <div class="card-header bg-info text-white d-flex align-items-center">
                <i class="bi bi-graph-up me-2"></i> 
                <strong>Resumo de Atividades</strong>
            </div>
            <div class="card-body">
                <p>Aqui você pode ver o resumo das atividades mais recentes no sistema, como a quantidade de usuários cadastrados e a quantidade de produtos disponíveis.</p>
                <!-- Aqui você pode adicionar gráficos, números ou outros resumos -->
                <!-- Consider adding a chart or summary statistics here -->
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../src/template/admin/footer.php'; ?>
