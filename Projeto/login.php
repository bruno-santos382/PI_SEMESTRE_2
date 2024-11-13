<?php 

$template = array(
    'titulo' =>  'Login &mdash; GM Supermercado',
    'scripts' => ['static/js/login.js'],
    'styles' => ['static/css/login.css']
);
include __DIR__ . '/src/template/header.php';

?>

<div class="row justify-content-center">
    <div class="col-md-4">
        <div class="text-center mt-5">
            <img src="static/img/logo.png" alt="Logo" class="logo">
        </div>
        <!-- Formulário de Login -->
        <div class="card mt-3 p-4">
            <h3 class="text-center">Bem-Vindo ao GM Supermercado, faça seu login!</h3>

            <!-- Caixa de alerta -->
            <?php include __DIR__ . '/src/template/alertas.php' ?>

            <form id="loginForm" action="#" method="POST">
                <div class="form-group mb-3">
                    <label for="login">Login</label>
                    <input type="text" name="login" class="form-control" id="login" placeholder="Digite o seu usuário, e-mail ou telefone">
                </div>
                <div class="form-group mb-3">
                    <label for="senha">Senha</label>
                    <input type="password" name="senha" class="form-control" id="senha" placeholder="Digite sua senha">
                </div>
                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary">Entrar</button>
                </div>
            </form>
            <div class="text-center mt-3">
                <a href="#">Esqueceu sua senha?</a>
            </div>
        </div>
        <!-- Link para Registrar -->
        <div class="text-center mt-3">
            <p>Não tem uma conta? <a href="#">Registre-se aqui</a></p>
        </div>
    </div>
</div>

<?php include __DIR__ . '/src/template/footer.php'; ?>