<?php 

$template = array(
    'titulo' =>  'Cadastro de Produtos &mdash; Admin',
    'scripts' => ['./static/js/login.js'],
    'verifica_login' => false
);

include __DIR__ . '/../src/template/admin/header.php';
?>

<h2>Login</h2>
<form id="loginForm" action="#" method="POST" >
    <input type="text" name="usuario" required>
    <input type="password" name="senha" required>
    <button type="submit">Acessar</button>
</form>

<?php include __DIR__ . '/../src/template/admin/footer.php'; ?>