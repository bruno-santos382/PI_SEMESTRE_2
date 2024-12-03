<?php 

$template = array(
    'titulo' =>  'Gerenciar Usuários &mdash; Admin',
    'scripts' => ['./static/js/admin/gerenciar_usuarios.js'],
    'menu_atual' => 'gerenciar_usuarios',
);

include __DIR__ . '/../src/template/admin/header.php';
?>

<h2 class="text-center my-4">Gerenciar Usuários</h2>

<div id="alertaUsuario" class="mb-3">
    <?php include __DIR__ . '/../src/template/alertas.php'; ?>
</div>

<table class="table table-centered table-sm table-hover table-borderless">
    <thead class="table-secondary">
        <tr>
            <th scope="col" style="width: 50px;">ID</th>
            <th scope="col">Usuário</th>
            <th scope="col" style="width: 200px;">Cadastrado em</th>
            <th scope="col" style="width: 200px;">Ações</th>
        </tr>
    </thead>
    <tbody>
        <?php 
            require_once __DIR__.'/../src/class/usuario/Usuario.php';
            $usuario = new Usuario();
        ?>
        <?php foreach ($usuario->listarTudo() as $item): ?>
            <tr>
                <td><?= $item['IdUsuario'] ?></td>
                <td><?= $item['Usuario'] ?></td>
                <td><?= date('d/m/Y H:i', strtotime($item['DataCriacao'])) ?></td>
                <td>
                    <a href="admin/cadastro_usuario.php?id=<?= $item['IdUsuario'] ?>" class="btn btn-warning btn-sm">
                        <i class="bi bi-pencil-fill me-1"></i> Editar
                    </a>
                    <button type="button" class="btn btn-danger btn-sm" onclick="removerUsuario(this, '<?= $item['IdUsuario'] ?>')">
                        <i class="bi bi-trash-fill me-1"></i> Excluír
                    </button>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>



<?php include __DIR__ . '/../src/template/admin/footer.php'; ?>