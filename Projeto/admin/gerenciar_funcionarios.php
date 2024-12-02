<?php 

$template = array(
    'titulo' => 'Gerenciar Funcionários &mdash; Admin',
    'scripts' => ['./static/js/admin/gerenciar_funcionarios.js'], // Script específico para funcionários
    'menu_atual' => 'gerenciar_funcionarios', // Menu destacado como atual
);

include __DIR__ . '/../src/template/admin/header.php';
?>

<h2 class="text-center my-4">Gerenciar Funcionários</h2>

<div id="alertaFuncionario" class="mb-3">
    <?php include __DIR__ . '/../src/template/alertas.php'; ?>
</div>

<table class="table table-centered table-sm table-hover table-borderless">
    <thead class="table-secondary">
        <tr>
            <th scope="col" style="width: 50px;">ID</th>
            <th scope="col">Nome</th>
            <th scope="col">Email</th>
            <th scope="col" style="width: 150px;">Telefone</th>
            <th scope="col">Login</th>
            <th scope="col" style="width: 200px;">Cadastrado em</th>
            <th scope="col" style="width: 200px;">Ações</th>
        </tr>
    </thead>
    <tbody>
        <?php 
            require_once __DIR__.'/../src/class/funcionario/Funcionario.php';
            $funcionario = new Funcionario(); // Instância específica para Funcionário
        ?>
        <?php foreach ($funcionario->listarTudo() as $item): ?>
            <tr>
                <td><?= $item['IdPessoa'] ?></td>
                <td><?= $item['Nome'] ?></td>
                <td><?= $item['Email'] ?></td>
                <td><?= $item['Telefone'] ?></td>
                <td><?= $item['Usuario'] ?? '<b class="text-danger">(Sem acesso)</b>' ?></td>
                <td><?= date('d/m/Y H:i', strtotime($item['DataCriacao'])) ?></td>
                <td>
                    <a href="admin/cadastro_funcionario.php?id=<?= $item['IdPessoa'] ?>" class="btn btn-warning btn-sm">
                        <i class="bi bi-pencil-fill me-1"></i> Editar
                    </a>
                    <button type="button" class="btn btn-danger btn-sm" onclick="removerFuncionario(this, '<?= $item['IdPessoa'] ?>')">
                        <i class="bi bi-trash-fill me-1"></i> Excluír
                    </button>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php include __DIR__ . '/../src/template/admin/footer.php'; ?>