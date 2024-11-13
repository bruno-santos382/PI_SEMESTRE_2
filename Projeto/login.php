<?php 

$template = array(
    'titulo' =>  'Cadastro de Produtos &mdash; Admin',
    'scripts' => ['static/js/login.js'],
    'verifica_login' => false
);

include __DIR__ . '/src/template/header.php';
?>

<h2>Login</h2>
<form id="loginForm" action="#" method="POST" >
    <input type="text" name="login" placeholder="Digite o usuário, email ou telefone" required>
    <input type="password" name="senha" placeholder="Digite sua senha" required>
    <button type="submit">Acessar</button>
</form>

<?php include __DIR__ . '/src/template/footer.php'; ?>