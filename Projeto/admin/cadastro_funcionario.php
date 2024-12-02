<?php 

$template = array(
    'titulo' =>  'Cadastro de Funcionários &mdash; GM Supermercado',
    'menu_atual' => 'cadastro_funcionario',
    'scripts' => ['./static/js/admin/cadastro_funcionario.js']
);

include __DIR__ . '/../src/template/admin/header.php';
?>
<h2 class="text-center my-4">Cadastro de Funcionário</h2>

<div class="w-50 mx-auto">
    <div id="alertaFuncionario" class="mb-3">
        <?php include __DIR__. '/../src/template/alertas.php'; ?>   
    </div>

    <?php
        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

        $nome = null;
        $email = null;
        $telefone = null;
        $usuario_id = null;

        // Se houver um ID, busque os dados do funcionário
        if (!empty($id)) 
        {
            require __DIR__.'/../src/class/funcionario/Funcionario.php';
            $funcionario = new Funcionario();
            $funcionario = $funcionario->buscaPorId($id);

            if ($funcionario) {
                $nome = $funcionario['Nome'];
                $email = $funcionario['Email'];
                $telefone = $funcionario['Telefone'];
                $usuario_id = $funcionario['IdUsuario']; // Supondo que você tenha esse campo na tabela
            } else {
                $id = null;
            }
        }

        // Buscando usuários para preencher o select
        require __DIR__.'/../src/class/usuario/Usuario.php';
        $usuario_obj = new Usuario();
        $usuarios = $usuario_obj->listarTudo(); // Método que deve retornar todos os usuários
    ?>

    <form id="formCadastro" class="mb-3" action="" method="POST">
        <!-- Código do funcionário -->
        <input type="number" name="id" id="codigo" value="<?= $id ?>" hidden>

        <!-- Nome do Funcionário -->
        <div class="mb-3">
            <label for="nome" class="form-label fw-bold text-muted">Nome do Funcionário:</label>
            <input type="text" value="<?= $nome ?>" class="form-control" id="nome" name="nome" placeholder="Digite o nome do funcionário" required>
        </div>

        <!-- Email -->
        <div class="mb-3">
            <label for="email" class="form-label fw-bold text-muted">Email:</label>
            <input type="email" value="<?= $email ?>" class="form-control" id="email" name="email" placeholder="Digite o email do funcionário" required>
        </div>

        <!-- Telefone -->
        <div class="mb-3">
            <label for="telefone" class="form-label fw-bold text-muted">Telefone:</label>
            <input type="text" value="<?= $telefone ?>" class="form-control" id="telefone" name="telefone" placeholder="Digite o telefone do funcionário" required>
        </div>

        <!-- Seleção de Usuário -->
        <div class="mb-3">
            <label for="id_usuario" class="form-label fw-bold text-muted">Usuário:</label>
            <select name="id_usuario" id="id_usuario" class="form-select" required>
                <option value="" disabled selected>Selecione um usuário</option>
                <?php foreach ($usuarios as $usuario): ?>
                    <option value="<?= $usuario['IdUsuario'] ?>" <?= ($usuario_id == $usuario['IdUsuario']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($usuario['Usuario']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <!-- Botão de Cadastrar -->
        <div class="d-grid">
            <button type="submit" class="btn btn-primary btn-lg">Cadastrar</button>
        </div>
    </form>

</div>

<?php include __DIR__ . '/../src/template/admin/footer.php'; ?>