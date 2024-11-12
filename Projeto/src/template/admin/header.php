<?php 
$config = include __DIR__.'/../../includes/config.php';

 // Verificar se usuario está logado, a menos que seja desabilitado propositalmente
if (!isset($template['verifica_login']) || $template['verifica_login'] === true)
{
    require_once __DIR__.'/../../class/autenticacao/Autentica.php';

    $autentica = new Autentica();
    
    if (!$autentica->usuarioLogado()) {
        header('Location: ' . $config['app.url'] . '/admin/login.php');
        exit;
    }    
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <base href="<?= htmlspecialchars($config['app.url'].'/') ?>">
    <title><?= htmlspecialchars($template['titulo']) ?></title>

    <?php if (!empty($template['styles'])): ?>
        <?php foreach ($template['styles'] as $href): ?>
            <link rel="stylesheet" href="<?= htmlspecialchars($href) ?>">
        <?php endforeach; ?>
    <?php endif; ?>
</head>
<body>