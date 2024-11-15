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

    <?php if (!empty($template['styles'])): ?>
        <?php foreach ($template['styles'] as $href): ?>
            <link rel="stylesheet" href="<?= htmlspecialchars($href) ?>">
        <?php endforeach; ?>
    <?php endif; ?>
</head>

<body>

    <div class="container-fluid d-flex flex-row">
        <div class="d-flex flex-column flex-shrink-0 p-3 sidenav">
            <a href="/"
                class="d-flex w-100 flex-column align-items-center mb-3 mb-md-0 me-md-auto link-dark text-decoration-none">
                <img src="static/img/logo.png" width="100">
                <span class="fs-5">GM SuperMercado</span>
            </a>

            <hr>
            <ul class="nav nav-pills flex-column mb-auto">
                <!-- Home -->
                <li class="nav-item">
                    <a href="admin/" class="nav-link <?= $template['menu_atual'] == 'home' ? 'active' : 'link-dark' ?>"
                        aria-current="page">
                        <i class="bi bi-house-door me-2"></i> Início
                    </a>
                </li>

                <!-- Produtos -->
                <li class="nav-item">
                    <a href="#produtosSubmenu" class="nav-link link-dark" data-bs-toggle="collapse">
                        <i class="bi bi-box me-2"></i> Produtos
                    </a>
                    <ul class="collapse list-unstyled show" id="produtosSubmenu">
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

                    <!-- Usuários -->
                <li class="nav-item">
                    <a href="#usuariosSubmenu" class="nav-link link-dark" data-bs-toggle="collapse">
                        <i class="bi bi-person me-2"></i> Usuários
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

            </ul>

            <hr>

            <!-- Sair -->
            <a href="logout.php" class="btn btn-danger">
                <i class="bi bi-box-arrow-right me-2"></i> Sair
            </a>

        </div>

        <div class="container">