<?php 

$template = array(
    'titulo' =>  'Cadastro de Clientes &mdash; GM Supermercado',
    'menu_atual' => 'cadastro_cliente',
    'scripts' => ['./static/js/admin/cadastro_cliente.js']
);

include __DIR__ . '/../src/template/admin/header.php';
?>
<h2 class="text-center my-4">Cadastro de Cliente</h2>

<div class="w-50 mx-auto">
    <div id="alertaCliente" class="mb-3">
        <?php include __DIR__. '/../src/template/alertas.php'; ?>   
    </div>

    <?php
        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

        $nome = null;
        $email = null;
        $telefone = null;
        $usuario_id = null;

        // Se houver um ID, busque os dados do cliente
        if (!empty($id)) 
        {
            require __DIR__.'/../src/class/cliente/Cliente.php';
            $cliente = new Cliente();
            $cliente = $cliente->buscaPorId($id);

            if ($cliente) {
                $nome = $cliente['Nome'];
                $email = $cliente['Email'];
                $telefone = $cliente['Telefone'];
                $usuario_id = $cliente['IdUsuario']; // Supondo que você tenha esse campo na tabela
            } else {
                $id = null;
            }
        }

        // Buscando usuários para preencher o select
        require __DIR__.'/../src/class/usuario/Usuario.php';
        $usuario_obj = new Usuario();
        $usuarios = $usuario_obj->listarPorTipo('cliente'); // Método que deve retornar todos os usuários
    ?>

    <form id="formCadastro" class="mb-3" action="" method="POST">
        <!-- Código do cliente -->
        <input type="number" name="id" id="codigo" value="<?= $id ?>" hidden>

        <!-- Nome do Cliente -->
        <div class="mb-3">
            <label for="nome" class="form-label fw-bold text-muted">Nome do Cliente:</label>
            <input type="text" value="<?= $nome ?>" class="form-control" id="nome" name="nome" placeholder="Digite o nome do cliente" required>
        </div>

        <!-- Email -->
        <div class="mb-3">
            <label for="email" class="form-label fw-bold text-muted">Email:</label>
            <input type="email" value="<?= $email ?>" class="form-control" id="email" name="email" placeholder="Digite o email do cliente" required>
        </div>

        <!-- Telefone -->
        <div class="mb-3">
            <label for="telefone" class="form-label fw-bold text-muted">Telefone:</label>
            <input type="text" value="<?= $telefone ?>" class="form-control" id="telefone" name="telefone" placeholder="Digite o telefone do cliente" required>
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