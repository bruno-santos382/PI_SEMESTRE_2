<?php 

$template = array(
    'titulo' =>  'Cadastro de Usuários &mdash; GM Supermercado',
    'menu_atual' => 'cadastro_usuario',
    'scripts' => ['./static/js/admin/cadastro_usuario.js']
);

include __DIR__ . '/../src/template/admin/header.php';
?>
<h2 class="text-center my-4">Cadastro de Usuário</h2>

<div class="w-50 mx-auto">
    <div id="alertaUsuario">
        <?php include __DIR__. '/../src/template/alertas.php'; ?>   
    </div>

    <?php
        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

        $nome = null;
        $senha = null;
        $tipo_usuario = null;
        
        if (!empty($id)) 
        {
            require __DIR__.'/../src/class/usuario/Usuario.php';
            $usuario = new Usuario();
            $usuario = $usuario->buscaPorId($id);

            if ($usuario) {
                $nome = $usuario['Usuario'];
                $senha = $usuario['Senha'];
                $tipo_usuario = $usuario['TipoUsuario'];
            } else {
                $id = null;
            }
        }
    ?>

    <form id="formCadastro" class="mb-3" action="" method="POST">
        <!-- Código do usuário -->
        <input type="number" name="id" id="codigo" value="<?= $id ?>" hidden>

        <!-- Nome do Usuário -->
        <div class="mb-3">
            <label for="nome" class="form-label fw-bold text-muted">Nome do Usuário:</label>
            <input type="text" value="<?= $nome ?>" class="form-control" id="usuario" name="usuario" placeholder="Digite o nome do usuário" required>
        </div>

        <!-- Senha -->
        <div class="mb-3">
            <label for="senha" class="form-label fw-bold text-muted">Senha:</label>
            <input type="password" class="form-control" id="senha" name="senha" placeholder="Digite a senha do usuário">
            
            <?php if (!empty($id)): ?>
                <small class="form-text text-muted">Deixe em branco para manter a senha atual.</small>
            <?php endif; ?>
        </div>

        <!-- Tipo de Usuário -->
        <div class="mb-3">
            <label for="tipo_usuario" class="form-label fw-bold text-muted">Tipo de Usuário:</label>
            <select name="tipo_usuario" id="tipo_usuario" class="form-select" required>
                <option value="" disabled selected>Selecione o tipo de usuário</option>
                <option value="cliente" <?= $tipo_usuario === 'cliente' ? 'selected' : '' ?>>Cliente</option>
                <option value="funcionario" <?= $tipo_usuario === 'funcionario' ? 'selected' : '' ?>>Funcionário</option>
            </select>
        </div>

        <!-- Botão de Cadastrar -->
        <div class="d-grid">
            <button type="submit" class="btn btn-primary btn-lg">Cadastrar</button>
        </div>
    </form>

</div>

<?php include __DIR__ . '/../src/template/admin/footer.php'; ?>
