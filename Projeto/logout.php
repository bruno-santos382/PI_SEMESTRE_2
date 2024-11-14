<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logout &mdash; GM Supermercado</title>
</head>
<body>
    Por favor, aguarde...

    <?php
        require __DIR__.'/src/class/autenticacao/Autentica.php';
        $autentica = new Autentica();
        $autentica->logout();
        header('Location: index.php');
    ?>
</body>
</html>