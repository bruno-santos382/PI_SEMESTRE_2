<?php

$pedido_id = filter_input(INPUT_GET, 'pedido', FILTER_VALIDATE_INT);

if (empty($pedido_id)) {
    header('Location: index.php');
    exit;
}

require_once __DIR__ . '/src/class/pedido/Pedido.php';
require_once __DIR__ . '/src/class/autenticacao/Autentica.php';

$autentica = new Autentica();
$usuario = $autentica->usuarioLogado();

$pedido_obj = new Pedido();
$pedido = $pedido_obj->buscaPorId($pedido_id);

if (!$usuario || $pedido['IdPessoa'] !== $usuario['id_pessoa']) {
    header('Location: index.php');
    exit;
}

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comprovante - Pedido #<?= $pedido_id ?></title>
    <!-- CSS do Bootstrap -->
    <link href="static/lib/bootstrap-5.3.3/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }

        .invoice {
            border: 1px solid #ccc;
            padding: 50px;
            margin-top: 50px;
        }

        
        .header, .footer {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h1, .footer p {
            margin: 0;
        }
        .details div {
            margin-bottom: 10px;
        }
        .total {
            font-weight: bold;
            font-size: larger;
            background-color: #e9ecef;
            padding: 10px;
            border-radius: 5px;
        }
        /* Esconde os botões na impressão */
        @media print {
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body>

<div class="container">
    <div class="invoice">
        <div class="header">
            <h1>Comprovante</h1>
            <p>GM Supermercado</p>
            <p>Endereço do Supermercado</p>
            <p>Telefone: (XX) XXXX-XXXX</p>
        </div>

        <div class="details mb-4">
            <div><strong>Data:</strong> <?= date('d/m/Y', strtotime($pedido['DataPedido'])) ?></div>
            <div><strong>N° Pedido:</strong> <?= $pedido['IdPedido'] ?></div>
            <div><strong>Nome do Cliente:</strong> <?= $pedido['ClienteNome'] ?></div>
            <div><strong>Email do Cliente:</strong> <?= $pedido['ClienteEmail'] ?></div>
        </div>

        <table class="table table-bordered table-sm">
            <thead class="thead-light">
                <tr>
                    <th>Descrição</th>
                    <th class="text-center">Quantidade</th>
                    <th class="text-center">Preço Unitário</th>
                    <th class="text-center">Total</th>
                </tr>
            </thead>
            <tbody>
                <?php ['total'=>$total, 'itens'=>$produtos] = $pedido_obj->listaItensPedido($pedido_id); ?>
                <?php foreach ($produtos as $item): ?>
                    <tr>
                        <td><?= $item['Nome'] ?></td>
                        <td class="text-center"><?= $item['Quantidade'] ?></td>
                        <td class="text-center">R$ <?= number_format($item['PrecoUnitario'], 2, ',', '.') ?></td>
                        <td class="text-center">R$ <?= number_format($item['ValorTotal'], 2, ',', '.') ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div class="total text-end mb-4">
            Total a Pagar: R$ <?= number_format($total, 2, ',', '.') ?>
        </div>

        <div class="footer mb-4">
            <p>Obrigado pela sua compra!</p>
        </div>

        <!-- Botões de Impressão e Compartilhamento -->
        <div class="text-center no-print">
            <button type="button" onclick="window.print()" class="btn btn-primary">Imprimir</button>
            <button type="button" class="btn btn-danger" onclick="window.close()">Fechar</button>
        </div>
    </div>
</div>

</body>
</html>
