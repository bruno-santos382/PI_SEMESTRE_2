<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Produto</title>
</head>
<body>
    <h1>Cadastro de Produto</h1>
    <!-- Form to submit product details -->
    <form id="formCadastro" action="" method="POST">
        <div>
            <label for="nome">Nome do Produto:</label>
            <input type="text" id="nome" name="nome" required>
        </div>

        <div>
            <label for="preco">Preço:</label>
            <input type="text" id="preco" name="preco" required>
        </div>

        <div>
            <label for="marca">Marca:</label>
            <input type="text" id="marca" name="marca" required>
        </div>

        <div>
            <label for="estoque">Quantidade em Estoque:</label>
            <input type="number" id="estoque" name="estoque" required>
        </div>

        <div>
            <button type="submit">Cadastrar</button>
        </div>
    </form>

    <script src="./static/js/cadastro_produto.js"></script>
</body>
</html>
