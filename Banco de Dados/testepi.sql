-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 29/10/2024 às 21:00
-- Versão do servidor: 10.4.32-MariaDB
-- Versão do PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

CREATE DATABASE IF NOT EXISTS mercado;
USE mercado;

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `testepi`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `adicionados`
--

CREATE TABLE `adicionados` (
  `Quantidade` int(11) DEFAULT NULL,
  `fk_Carrinho_IdCarrinho` int(11) DEFAULT NULL,
  `fk_Produtos_IdProduto` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `adicionados`
--

INSERT INTO `adicionados` (`Quantidade`, `fk_Carrinho_IdCarrinho`, `fk_Produtos_IdProduto`) VALUES
(2, 1, 1),
(1, 1, 3),
(4, 2, 2),
(1, 3, 5);

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
-- Estrutura tabela de usuários
--

CREATE TABLE `usuarios` (
  IdUsuario INT AUTO_INCREMENT PRIMARY KEY,
  Usuario VARCHAR(50) DEFAULT NULL,
  Email VARCHAR(100) DEFAULT NULL,
  Telefone VARCHAR(20) DEFAULT NULL,
  Senha VARCHAR(255) NOT NULL,
  DataCriacao DATETIME DEFAULT CURRENT_TIMESTAMP(),
  DataExclusao DATETIME DEFAULT NULL,
  UNIQUE KEY `idx_email` (`Email`),
  UNIQUE KEY `idx_telefone` (`Telefone`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Usuario padrão, usuario: admin, senha: admin
INSERT INTO usuarios (Usuario, Senha) VALUES ('admin', '$2y$10$I5cV9q6YCgQkUMPA1e83seoNnpAvlwO4oil84KMACnLfFWPLswnkO');

--
-- Estrutura tabela de permissões dos usuários
--

CREATE TABLE `permissao_usuarios` (
  IdPermissaoUsuario INT AUTO_INCREMENT PRIMARY KEY,
  IdUsuario INT NOT NULL,
  Permissao VARCHAR(255) NOT NULL,  -- Mudança: Usando Permissao como string
  DataCriacao DATETIME DEFAULT CURRENT_TIMESTAMP(),
  DataExclusao DATETIME DEFAULT NULL,
  FOREIGN KEY (IdUsuario) REFERENCES usuarios(IdUsuario) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO permissao_usuarios (IdUsuario, Permissao) VALUES (1, 'acesso_admin');


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
(1, '2024-10-10', '2024-10-12', 45, 'Finalizado', 1),
(2, '2024-10-15', '2024-10-16', 20, 'Em Andamento', 2),
(3, '2024-10-20', '2024-10-22', 33.5, 'Cancelado', 3),
(4, '2024-10-25', NULL, 10, 'Em Andamento', 1);

-- --------------------------------------------------------

--
-- Estrutura para tabela `possuem`
--

CREATE TABLE `possuem` (
  `fk_Pedidos_IdPedido` int(11) DEFAULT NULL,
  `fk_Produtos_IdProduto` int(11) DEFAULT NULL,
  `Quantidade` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `possuem`
--

INSERT INTO `possuem` (`fk_Pedidos_IdPedido`, `fk_Produtos_IdProduto`, `Quantidade`) VALUES
(1, 1, 2),
(1, 2, 3),
(2, 3, 5),
(3, 4, 1),
(4, 5, 1);

-- --------------------------------------------------------


-- Estrutura tabela de imagens com AUTO_INCREMENT para IdImagem
CREATE TABLE imagens (
  IdImagem INT AUTO_INCREMENT PRIMARY KEY,  -- Definindo o ID para auto incremento
  NomeImagem VARCHAR(255) DEFAULT NULL,
  Caminho VARCHAR(255) NOT NULL,
  DataCriacao DATETIME DEFAULT CURRENT_TIMESTAMP(),
  DataExclusao DATETIME DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Inserindo dados na tabela `imagens` com IDs definidos manualmente
INSERT INTO imagens (IdImagem, NomeImagem, Caminho) VALUES
(1, 'abacaxi.jpg', 'static/img/upload/abacaxi.jpg'),
(2, 'abobora.png', 'static/img/upload/abobora.png'),
(3, 'alface.webp', 'static/img/upload/alface.webp'),
(4, 'banana.png', 'static/img/upload/banana.png'),
(5, 'batata.png', 'static/img/upload/Batata.png'),
(6, 'batatadoce.webp', 'static/img/upload/batatadoce.webp'),
(7, 'brocolis.png', 'static/img/upload/brocolis.png'),
(8, 'carnemoida.webp', 'static/img/upload/carnemoida.webp'),
(9, 'cebolinha.png', 'static/img/upload/cebolinha.png'),
(10, 'cebolinha.webp', 'static/img/upload/cebolinha.webp'),
(11, 'cenoura.png', 'static/img/upload/cenoura.png'),
(12, 'cereja.webp', 'static/img/upload/cereja.webp'),
(13, 'couve.png', 'static/img/upload/couve.png'),
(14, 'escarola.webp', 'static/img/upload/escarola.webp'),
(15, 'espinafre-1000x1000.jpg', 'static/img/upload/espinafre[1]-1000x1000.jpg'),
(16, 'frango.jpg', 'static/img/upload/frango.jpg'),
(17, 'kiwi.png', 'static/img/upload/kiwi.png'),
(18, 'mamao.webp', 'static/img/upload/mamao.webp'),
(19, 'mandioca.webp', 'static/img/upload/Mandioca.webp'),
(20, 'manga.webp', 'static/img/upload/manga.webp'),
(21, 'maca.jpg', 'static/img/upload/maca.jpg'),
(22, 'melancia.png', 'static/img/upload/melancia.png'),
(23, 'morangos.jpg', 'static/img/upload/morangos1.jpg'),
(24, 'pepino.webp', 'static/img/upload/pepino.webp'),
(25, 'pimentao.png', 'static/img/upload/Pimentao.png'),
(26, 'rabanete.png', 'static/img/upload/rabanete.png'),
(27, 'rucula.png', 'static/img/upload/rucula.png'),
(28, 'salsinha.jpg', 'static/img/upload/salcinha.jpg'),
(29, 'suco.jpg', 'static/img/upload/suco.jpg'),
(30, 'tomate.jpg', 'static/img/upload/tomate.jpg'),
(31, 'uva-francesa.webp', 'static/img/upload/uvafrancesa.webp'),
(32, 'uva-verde.jpg', 'static/img/upload/uvaverde.jpg');


--
-- Estrutura para tabela `produtos`
--

CREATE TABLE `produtos` (
  `IdProduto` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `Nome` VARCHAR(40) DEFAULT NULL,
  `Preco` FLOAT DEFAULT NULL,
  `Marca` VARCHAR(40) DEFAULT NULL,
  `Estoque` INT(11) DEFAULT NULL,
  `DataCriacao` DATETIME DEFAULT CURRENT_TIMESTAMP(),
  `DataExclusao` DATETIME DEFAULT NULL,
  `IdImagem` INT(11) DEFAULT NULL,
  CONSTRAINT `fk_produtos_imagens` FOREIGN KEY (`IdImagem`) REFERENCES `imagens`(`IdImagem`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Inserindo dados para a tabela `produtos`
INSERT INTO `produtos` (`Nome`, `Preco`, `Marca`, `Estoque`, `IdImagem`) VALUES
('Arroz', 15, 'Marca A', 100, 1),
('Feijão', 8.5, 'Marca B', 150, 2),
('Açúcar', 4, 'Marca C', 200, 3),
('Óleo', 7.2, 'Marca D', 120, 4),
('Café', 10, 'Marca E', 80, 5);


--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `carrinho`
--
ALTER TABLE `carrinho`
  ADD PRIMARY KEY (`IdCarrinho`),
  MODIFY `IdCarrinho` INT AUTO_INCREMENT;
--
-- Índices de tabela `clientes`
--
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`IdCliente`),
  MODIFY `IdCliente` INT AUTO_INCREMENT;

--
-- Índices de tabela `pedidos`
--
ALTER TABLE `pedidos`
  ADD PRIMARY KEY (`IdPedido`),
  MODIFY `IdPedido` INT AUTO_INCREMENT;


-------------------------------------------------------------------------------

DELIMITER $$

-- Descrição: Este trigger é responsável por definir o campo IdImagem como NULL em todos os produtos
-- que estão referenciando uma imagem quando a DataExclusao da imagem é definida.
CREATE TRIGGER set_produto_imagem_null_on_exclusao
BEFORE UPDATE ON imagens
FOR EACH ROW
BEGIN
  -- Verifica se a data de exclusão foi alterada para não nula
  IF NEW.DataExclusao IS NOT NULL AND OLD.DataExclusao IS NULL THEN
    -- Define o campo IdImagem como NULL nos produtos que referenciam a imagem excluída
    UPDATE produtos
    SET IdImagem = NULL
    WHERE IdImagem = OLD.IdImagem;
  END IF;
END $$

DELIMITER ;


/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
