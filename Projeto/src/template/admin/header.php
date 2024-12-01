<?php
$config = include __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../class/autenticacao/Autentica.php';

// Verificar se usuario está logado
$autentica = new Autentica();
$usuario = $autentica->usuarioLogado();
if (!$usuario || !in_array('acesso_admin', $usuario['permissoes'])) {
    header('Location: ' . $config['app.url'] . '/login.php');
    exit;
}

if (empty($template['menu_atual'])) {
    $template['menu_atual'] = 'home';
}

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <base href="<?= htmlspecialchars($config['app.url'] . '/') ?>">
    <title><?= $template['titulo'] ?></title>

    <link rel="stylesheet" href="static/css/main.css">
    <link rel="stylesheet" href="static/css/admin.css">
    <link href="static/lib/bootstrap-5.3.3/css/bootstrap.min.css" rel="stylesheet">
    <link href="static/lib/bootstrap-icons-1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <?php if (!empty($template['styles']) && is_array($template['styles'])): ?>
        <?php foreach ($template['styles'] as $href): ?>
            <link rel="stylesheet" href="<?= htmlspecialchars($href) ?>">
        <?php endforeach; ?>
    <?php endif; ?>
</head>

<body>

    <div class="container-fluid d-flex flex-row">
        <div class="d-flex flex-column flex-shrink-0 p-3 sidenav">
            <a href="./"
                class="d-flex w-100 flex-column align-items-center mb-3 mb-md-0 me-md-auto link-dark text-decoration-none">
                <img src="static/img/logo.png" width="100">
                <span class="fs-5">GM SuperMercado</span>
            </a>

            <hr>
            <div class="overflow-y-scroll h-100" >
                <ul class="nav nav-pills flex-column mb-auto">
                    <!-- Home -->
                    <li class="nav-item">
                        <a href="admin/" class="nav-link <?= $template['menu_atual'] == 'home' ? 'active' : 'link-dark' ?>"
                            aria-current="page">
                            <i class="bi bi-house-door-fill me-2"></i> Início
                        </a>
                    </li>
                    <!-- Pedidos -->
                    <li class="nav-item">
                        <a href="admin/pedidos.php" class="<?= $template['menu_atual'] == 'pedidos' ? 'active' : 'link-dark' ?> nav-link" >
                            <i class="bi bi-cart4 me-2"></i> Pedidos
                        </a>
                    </li>
                    <!-- Produtos -->
                    <li class="nav-item">
                        <a href="#produtosSubmenu" class="nav-link link-dark" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="produtosSubmenu">
                            <i class="bi bi-box-fill me-2"></i> Produtos
                        </a>
                        <div >
                            <ul id="produtosSubmenu" class="collapse list-unstyled show">
                                <li>
                                    <a href="admin/cadastro_produto.php"
                                        class="nav-link ms-4 <?= $template['menu_atual'] == 'cadastro_produto' ? 'active' : 'link-dark' ?>">
                                        <i class="bi bi-plus me-2"></i> Novo
                                    </a>
                                </li>
                                <li>
                                    <a href="admin/gerenciar_produtos.php"
                                        class="nav-link ms-4 <?= $template['menu_atual'] == 'gerenciar_produtos' ? 'active' : 'link-dark' ?>">
                                        <i class="bi bi-plus me-2"></i> Gerenciar
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    <!-- Categorias -->
                    <li class="nav-item">
                        <a href="admin/categorias.php" class="<?= $template['menu_atual'] == 'categorias' ? 'active' : 'link-dark' ?> nav-link" >
                            <i class="bi bi-tag-fill me-2"></i> Categorias
                        </a>
                    </li>
                     <!-- Promoções -->
                     <li class="nav-item">
                        <a href="admin/promocoes.php" class="<?= $template['menu_atual'] == 'promocoes' ? 'active' : 'link-dark' ?> nav-link" >
                            <i class="bi bi-percent me-2"></i> Promoções
                        </a>
                    </li>
                    <!-- Usuários -->
                    <li class="nav-item">
                        <a href="#usuariosSubmenu" class="nav-link link-dark" data-bs-toggle="collapse">
                            <i class="bi bi-person-fill me-2"></i> Usuários
                        </a>
                        <ul class="collapse list-unstyled show" id="usuariosSubmenu">
                            <li>
                                <a href="admin/cadastro_usuario.php"
                                    class="nav-link ms-4 <?= $template['menu_atual'] == 'cadastro_usuario' ? 'active' : 'link-dark' ?>">
                                    <i class="bi bi-plus me-2"></i> Novo
                                </a>
                            </li>
                            <li>
                                <a href="admin/gerenciar_usuarios.php"
                                    class="nav-link ms-4 <?= $template['menu_atual'] == 'gerenciar_usuarios' ? 'active' : 'link-dark' ?>">
                                    <i class="bi bi-plus me-2"></i> Gerenciar
                                </a>
                            </li>
                        </ul>
                    </li>
                    <!-- Clientes -->
                    <li class="nav-item">
                        <a href="#clientesSubmenu" class="nav-link link-dark" data-bs-toggle="collapse">
                            <i class="bi bi-person-fill me-2"></i> Clientes
                        </a>
                        <ul class="collapse list-unstyled show" id="clientesSubmenu">
                            <li>
                                <a href="admin/cadastro_cliente.php"
                                    class="nav-link ms-4 <?= $template['menu_atual'] == 'cadastro_cliente' ? 'active' : 'link-dark' ?>">
                                    <i class="bi bi-plus me-2"></i> Novo
                                </a>
                            </li>
                            <li>
                                <a href="admin/gerenciar_clientes.php"
                                    class="nav-link ms-4 <?= $template['menu_atual'] == 'gerenciar_clientes' ? 'active' : 'link-dark' ?>">
                                    <i class="bi bi-plus me-2"></i> Gerenciar
                                </a>
                            </li>
                        </ul>
                    </li>
                    <!-- Funcionários -->
                    <li class="nav-item">
                        <a href="#funcionariosSubmenu" class="nav-link link-dark" data-bs-toggle="collapse">
                            <i class="bi bi-person-fill me-2"></i> Funcionários
                        </a>
                        <ul class="collapse list-unstyled show" id="funcionariosSubmenu">
                            <li>
                                <a href="admin/cadastro_funcionario.php"
                                    class="nav-link ms-4 <?= $template['menu_atual'] == 'cadastro_funcionario' ? 'active' : 'link-dark' ?>">
                                    <i class="bi bi-plus me-2"></i> Novo
                                </a>
                            </li>
                            <li>
                                <a href="admin/gerenciar_funcionarios.php"
                                    class="nav-link ms-4 <?= $template['menu_atual'] == 'gerenciar_funcionarios' ? 'active' : 'link-dark' ?>">
                                    <i class="bi bi-plus me-2"></i> Gerenciar
                                </a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>

            <hr>

            <!-- Sair -->
            <a href="logout.php" class="btn btn-danger">
                <i class="bi bi-box-arrow-right me-2"></i> Sair
            </a>

        </div>

        <div class="container">