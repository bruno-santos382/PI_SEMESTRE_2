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
        $permissoes_usuario = [];
        
        if (!empty($id)) 
        {
            require_once __DIR__.'/../src/class/usuario/Usuario.php';
            $usuario = new Usuario();
            $usuario = $usuario->buscaPorId($id);

            if ($usuario) {
                $nome = $usuario['Usuario'];
                $senha = $usuario['Senha'];
                $permissoes_usuario = $usuario['permissoes'];
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

        <?php
            // Permissões disponíveis
            $todas_permissoes = [
                'acesso_admin' => 'Acesso ao painel de administração',
            ];
        ?>

         <!-- Permissões -->
         <div class="mb-3">
            <label class="form-label fw-bold text-muted">Permissões:</label>
            <div class="border rounded p-3">
                <?php foreach ($todas_permissoes as $chave => $descricao): ?>
                    <div class="form-check">
                        <input 
                            type="checkbox" 
                            class="form-check-input" 
                            id="permissao_<?= $chave ?>" 
                            name="permissoes[]" 
                            value="<?= $chave ?>"
                            <?= in_array($chave, $permissoes_usuario) ? 'checked' : '' ?>
                        >
                        <label for="permissao_<?= $chave ?>" class="form-check-label"><?= $descricao ?></label>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>


        <!-- Botão de Cadastrar -->
        <div class="d-grid">
            <button type="submit" class="btn btn-primary btn-lg">Cadastrar</button>
        </div>
    </form>

</div>

<?php include __DIR__ . '/../src/template/admin/footer.php'; ?>
