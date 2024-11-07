<?php $config = include '../src/includes/config.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <base href="<?= htmlentities($config['app.url']) ?>">
    <title><?= $template['titulo'] ?></title>

    <?php if (!empty($template['styles'])): ?>
        <?php foreach ($template['styles'] as $href): ?>
            <link rel="stylesheet" href="<?= $href ?>">
        <?php endforeach; ?>
    <?php endif; ?>
</head>
<body>