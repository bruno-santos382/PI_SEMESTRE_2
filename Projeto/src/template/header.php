<?php 
    require_once __DIR__.'/../class/autenticacao/Autentica.php'; 
    $config = include __DIR__.'/../includes/config.php'; 
    $autentica = new Autentica();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <base href="<?= htmlspecialchars($config['app.url'].'/') ?>">
    <title><?= $template['titulo'] ?></title>

    <link rel="stylesheet" href="static/css/main.css">
    <link href="static/lib/bootstrap-5.3.3/css/bootstrap.min.css" rel="stylesheet">
    <link href="static/lib/bootstrap-icons-1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <?php if (!empty($template['styles'])): ?>
        <?php foreach ($template['styles'] as $href): ?>
            <link rel="stylesheet" href="<?= htmlspecialchars($href) ?>">
        <?php endforeach; ?>
    <?php endif; ?>
</head>
<body>
<!-- Navbar -->
<?php if (empty($template['esconder_navbar'])): ?>
    <nav class="navbar navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">
                <img src="static/img/logo.png" alt="Logo" width="30" height="24" class="d-inline-block align-text-top">
                GM SuperMercado
            </a>
            <div class="d-flex align-items-center">

                <?php if ($usuario = $autentica->usuarioLogado()): ?>
             
                    <?php if (in_array('acesso_admin', $usuario['permissoes'])): ?>
                        <a href="admin/" class="icon-text mx-3">
                            <img src="static/img/admin.png" alt="admin" class="icon-image">
                            <span>Administrar</span>
                        </a>
                    <?php endif; ?>

                    <a href="logout.php" class="icon-text mx-3">
                        <img src="static/img/sair.png" alt="Sair" class="icon-image">
                        <span>Sair</span>
                    </a>
                <?php else: ?>
                    <a href="login.php" class="icon-text mx-3">
                        <img src="static/img/login..png" alt="Login" class="icon-image">
                        <span>Login</span>
                    </a>
                <?php endif ?>

                <?php if (empty($usuario) || $usuario['tipo'] == 'cliente'): ?>
                    <a href="carrinho.php" class="icon-text mx-3">
                        <img src="static/img/compras.png" alt="Carrinho" class="icon-image">
                        <span>Carrinho</span>
                    </a>
                <?php endif; ?>
                
                <a href="index.php" class="icon-text mx-3">
                    <img src="static/img/casinha.png" alt="Localização" class="icon-image">
                    <span>Inicio</span>
                </a>

                <?php if (!empty($usuario)): ?>
                    <div class="icon-text mx-3">
                        <img src="static/img/login..png" alt="Usuário" class="icon-image">
                        <span><?= htmlspecialchars($usuario['usuario']) ?></span>
                    </div>
                <?php endif; ?>

            </div>
        </div>
    </nav>
<?php endif; ?>


<div class="container-fluid px-0">