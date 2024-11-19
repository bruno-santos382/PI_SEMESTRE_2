-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 19/11/2024 às 18:33
-- Versão do servidor: 10.4.32-MariaDB
-- Versão do PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `testedefinitivo`
--

DELIMITER $$
--
-- Procedimentos
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `PRC_ATUALIZA_ESTOQUE` (IN `idprod` INT, IN `qtde` INT, IN `operacao` CHAR(1))   BEGIN
  -- Verifica se a operação é 'E' (Entrada) ou 'S' (Saída)
    IF operacao = 'E' THEN
        -- Se for entrada, adiciona a quantidade ao estoque
        UPDATE produtos
        SET Estoque = Estoque + qtde
        WHERE IdProduto = idprod;
    ELSEIF operacao = 'S' THEN      
         UPDATE produtos
         SET Estoque = Estoque - qtde
         WHERE IdProduto = idprod;
    ELSE
        -- Se a operação não for nem 'E' nem 'S', lança um erro
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Operação inválida. Utilize ''E'' para entrada ou ''S'' para saída.';
    END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `PRC_FECHAR_PEDIDO` (IN `idcliente` INT)   BEGIN
    DECLARE idpedid INT;                   -- Variável para o ID do pedido
    DECLARE valor_total FLOAT DEFAULT 0;     -- Variável para o valor total do pedido
    DECLARE produto_id INT;                 -- ID do produto a ser processado
    DECLARE quantidad INT;                 -- Quantidade do produto
    DECLARE prec FLOAT;                    -- Preço do produto
    DECLARE estoque_atual INT;              -- Estoque do produto
    DECLARE idcar INT;                 -- ID do carrinho do cliente

    -- Declarando o manipulador e o cursor
    DECLARE done INT DEFAULT 0;
    DECLARE cur CURSOR FOR
        SELECT fk_Produtos_IdProduto, Quantidade
        FROM produtosCarrinho
        WHERE fk_Carrinho_IdCarrinho = (SELECT IdCarrinho FROM carrinho WHERE fk_Clientes_IdCliente = idcliente);

    -- Declarando o manipulador para quando o cursor não encontrar mais dados
    DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = 1;

    -- 1. Obter o ID do carrinho do cliente
    SELECT IdCarrinho INTO idcar
    FROM carrinho
    WHERE fk_Clientes_IdCliente = idcliente;

            -- 2. Criar o pedido
    		INSERT INTO pedidos (fk_Clientes_IdCliente, DataPedido, Estado)
    		VALUES (idcliente, CURDATE(), 'Em Andamento');
    
                -- Obter o ID do novo pedido criado
    		SET idpedid = LAST_INSERT_ID();



    -- 3. Abrir o cursor
    OPEN cur;

    -- 4. Loop para processar cada item do carrinho
    read_loop: LOOP
        FETCH cur INTO produto_id, quantidad;
        
        IF done THEN
            LEAVE read_loop;
        END IF;

        -- 5. Obter o preço do produto
        SELECT Preco INTO prec FROM produtos WHERE IdProduto = produto_id;

        -- 6. Calcular o valor total do pedido
        SET valor_total = valor_total + (prec * quantidad);

        -- 7. Inserir o produto no pedido (tabela `possuem`)
        INSERT INTO produtosPedido (fk_Pedidos_IdPedido, fk_Produtos_IdProduto, Quantidade)
        VALUES (idpedid, produto_id, quantidad);

        
    END LOOP;

    -- 9. Fechar o cursor
    CLOSE cur;
    
    -- 10. Atualizar o valor total do pedido
    UPDATE pedidos
    SET ValorTotal = valor_total
    WHERE IdPedido = idpedid;
    
    DELETE FROM produtoscarrinho WHERE fk_Carrinho_IdCarrinho = idcar;


END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Estrutura para tabela `carrinho`
--

CREATE TABLE `carrinho` (
  `IdCarrinho` int(11) NOT NULL,
  `fk_Clientes_IdCliente` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `carrinho`
--

INSERT INTO `carrinho` (`IdCarrinho`, `fk_Clientes_IdCliente`) VALUES
(1, 1),
(2, 2),
(3, 4);

-- --------------------------------------------------------

--
-- Estrutura para tabela `clientes`
--

CREATE TABLE `clientes` (
  `Nome` varchar(40) DEFAULT NULL,
  `IdCliente` int(11) NOT NULL,
  `Contato` varchar(40) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `clientes`
--

INSERT INTO `clientes` (`Nome`, `IdCliente`, `Contato`) VALUES
('João Silva', 1, 'joao@email.com'),
('Maria Oliveira', 2, 'maria@email.com'),
('Carlos Souza', 3, 'carlos@email.com'),
('Ana Lima', 4, 'ana@email.com');

-- --------------------------------------------------------

--
-- Estrutura para tabela `pedidos`
--

CREATE TABLE `pedidos` (
  `IdPedido` int(11) NOT NULL,
  `DataPedido` date DEFAULT NULL,
  `DataRetirada` date DEFAULT NULL,
  `ValorTotal` float DEFAULT NULL,
  `Estado` varchar(40) DEFAULT NULL,
  `fk_Clientes_IdCliente` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `pedidos`
--

INSERT INTO `pedidos` (`IdPedido`, `DataPedido`, `DataRetirada`, `ValorTotal`, `Estado`, `fk_Clientes_IdCliente`) VALUES
(1, '2024-10-10', '2024-10-12', 0, 'Finalizado', 1),
(2, '2024-10-15', '2024-10-16', 0, 'Em Andamento', 2),
(3, '2024-10-20', '2024-10-22', 0, 'Cancelado', 3),
(4, '2024-10-25', NULL, 0, 'Em Andamento', 1),
(5, '2024-11-13', NULL, 0, 'Em Andamento', 1),
(6, '2024-11-19', NULL, 38, 'Em Andamento', 1),
(7, '2024-11-19', NULL, 34, 'Em Andamento', 1);

-- --------------------------------------------------------

--
-- Estrutura para tabela `produtos`
--

CREATE TABLE `produtos` (
  `Nome` varchar(40) DEFAULT NULL,
  `IdProduto` int(11) NOT NULL,
  `Preco` float DEFAULT NULL,
  `Marca` varchar(40) DEFAULT NULL,
  `Estoque` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `produtos`
--

INSERT INTO `produtos` (`Nome`, `IdProduto`, `Preco`, `Marca`, `Estoque`) VALUES
('Arroz', 1, 15, 'Marca A', 96),
('Feijão', 2, 8.5, 'Marca B', 150),
('Açúcar', 3, 4, 'Marca C', 197),
('Óleo', 4, 7.2, 'Marca D', 120),
('Café', 5, 10, 'Marca E', 80);

-- --------------------------------------------------------

--
-- Estrutura para tabela `produtoscarrinho`
--

CREATE TABLE `produtoscarrinho` (
  `Quantidade` int(11) DEFAULT NULL,
  `fk_Carrinho_IdCarrinho` int(11) DEFAULT NULL,
  `fk_Produtos_IdProduto` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `produtoscarrinho`
--

INSERT INTO `produtoscarrinho` (`Quantidade`, `fk_Carrinho_IdCarrinho`, `fk_Produtos_IdProduto`) VALUES
(4, 2, 2),
(1, 3, 5),
(4, 2, 2),
(1, 3, 5);

-- --------------------------------------------------------

--
-- Estrutura para tabela `produtospedido`
--

CREATE TABLE `produtospedido` (
  `fk_Pedidos_IdPedido` int(11) DEFAULT NULL,
  `fk_Produtos_IdProduto` int(11) DEFAULT NULL,
  `Quantidade` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `produtospedido`
--

INSERT INTO `produtospedido` (`fk_Pedidos_IdPedido`, `fk_Produtos_IdProduto`, `Quantidade`) VALUES
(1, 1, 2),
(1, 2, 3),
(2, 3, 5),
(3, 4, 1),
(4, 5, 1),
(6, 1, 2),
(6, 3, 1),
(6, 3, 1),
(7, 1, 2),
(7, 3, 1);

--
-- Acionadores `produtospedido`
--
DELIMITER $$
CREATE TRIGGER `TRG_PRODUTOSPEDIDO_DELETE` BEFORE DELETE ON `produtospedido` FOR EACH ROW BEGIN
    -- Chama a procedure para adicionar o estoque do produto removido (pois o item foi retirado do pedido)
    CALL PRC_ATUALIZA_ESTOQUE(OLD.fk_Produtos_IdProduto, OLD.Quantidade, 'E');
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `TRG_PRODUTOSPEDIDO_INSERT` AFTER INSERT ON `produtospedido` FOR EACH ROW BEGIN
    -- Chama a procedure para subtrair o estoque do produto inserido (pois o item foi vendido)
    CALL PRC_ATUALIZA_ESTOQUE(NEW.fk_Produtos_IdProduto, NEW.Quantidade, 'S');
END
$$
DELIMITER ;

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `carrinho`
--
ALTER TABLE `carrinho`
  ADD PRIMARY KEY (`IdCarrinho`);

--
-- Índices de tabela `clientes`
--
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`IdCliente`);

--
-- Índices de tabela `pedidos`
--
ALTER TABLE `pedidos`
  ADD PRIMARY KEY (`IdPedido`);

--
-- Índices de tabela `produtos`
--
ALTER TABLE `produtos`
  ADD PRIMARY KEY (`IdProduto`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `carrinho`
--
ALTER TABLE `carrinho`
  MODIFY `IdCarrinho` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de tabela `clientes`
--
ALTER TABLE `clientes`
  MODIFY `IdCliente` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de tabela `pedidos`
--
ALTER TABLE `pedidos`
  MODIFY `IdPedido` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de tabela `produtos`
--
ALTER TABLE `produtos`
  MODIFY `IdProduto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
