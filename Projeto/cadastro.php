<?php 

$template = array(
    'titulo' =>  'Cadastro &mdash; GM Supermercado',
    'scripts' => ['static/js/cadastro.js'],
    'styles' => ['static/css/login.css'],
    'esconder_navbar' => true
);
include __DIR__ . '/src/template/header.php';

?>

<div class="row justify-content-center">
    <div class="col-md-4">
        <div class="text-center mt-5">
            <img src="static/img/logo.png" alt="Logo" class="logo">
        </div>
        <!-- Formulário de Cadastro -->
        <div class="card mt-3 p-4">
            <h3 class="text-center">Bem-Vindo ao GM Supermercado, crie sua conta!</h3>

            <!-- Caixa de alerta -->
            <div id="alertaCadastro" class="mb-3">
                <?php include __DIR__ . '/src/template/alertas.php' ?>
            </div>

            <form id="formCadastro" action="#" method="POST">
                <div class="form-group mb-3">
                    <label for="nome">Nome Completo</label>
                    <input type="text" name="nome" class="form-control" id="nome" placeholder="Digite seu nome completo" required>
                </div>
                <div class="form-group mb-3">
                    <label for="email">E-mail</label>
                    <input type="email" name="email" class="form-control" id="email" placeholder="Digite seu e-mail" required>
                </div>
                <div class="form-group mb-3">
                    <label for="telefone">Telefone</label>
                    <input type="tel" name="telefone" class="form-control" id="telefone" placeholder="Digite seu telefone" required>
                </div>
                <div class="form-group mb-3">
                    <label for="senha">Senha</label>
                    <input type="password" name="senha" class="form-control" id="senha" placeholder="Digite sua senha" required>
                </div>
                <div class="form-group mb-3">
                    <label for="confirmarSenha">Confirmar Senha</label>
                    <input type="password" name="confirmar_senha" class="form-control" id="confirmarSenha" placeholder="Confirme sua senha" required>
                </div>
                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary">Cadastrar</button>
                </div>
            </form>
            <div class="text-center mt-3">
                <a href="login.php">Já tem uma conta? Faça login</a>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/src/template/footer.php'; ?>
