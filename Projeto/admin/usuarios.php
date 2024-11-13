<?php 

$template = array(
    'titulo' =>  'Gerenciar Usuários &mdash; Admin',
    'scripts' => ['./static/js/admin/usuarios.js']
);

include __DIR__ . '/../src/template/admin/header.php';
?>

<h1>Usuários</h1>

<h1>Lista de Usuários</h1>

<table border="1">
    <thead>
        <tr>
            <th>ID</th>
            <th>Usuário</th>
            <th>Email</th>
            <th>Telefone</th>
            <th>Data de Criação</th>
        </tr>
    </thead>
    <tbody>
        <?php
            require_once __DIR__.'/../src/class/usuario/Usuario.php';
            $usuario = new Usuario();
            $lista_usuarios = $usuario->buscar();
        ?>

        <?php foreach ($lista_usuarios as $usuario): ?>
            <tr>
                <td><?php echo $usuario['IdUsuario']; ?></td>
                <td><?php echo $usuario['Usuario']; ?></td>
                <td><?php echo $usuario['Email']; ?></td>
                <td><?php echo $usuario['Telefone']; ?></td>
                <td><?php echo date('d/m/Y - H:i', strtotime($usuario['DataCriacao'])); ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php include __DIR__ . '/../src/template/admin/footer.php'; ?>