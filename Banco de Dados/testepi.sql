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
  Telefone VARCHAR(20) DEFAULT NULL,  -- Fixed typo here
  Senha VARCHAR(255) NOT NULL,
  DataExclusao DATETIME DEFAULT NULL,
  UNIQUE KEY `idx_email` (`Email`),  -- Optional: to ensure email is unique
  UNIQUE KEY `idx_telefone` (`Telefone`)  -- Optional: to ensure telefone is unique
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
('Arroz', 1, 15, 'Marca A', 100),
('Feijão', 2, 8.5, 'Marca B', 150),
('Açúcar', 3, 4, 'Marca C', 200),
('Óleo', 4, 7.2, 'Marca D', 120),
('Café', 5, 10, 'Marca E', 80);

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

--
-- Índices de tabela `produtos`
--
ALTER TABLE `produtos`
  ADD PRIMARY KEY (`IdProduto`),
  MODIFY `IdProduto` INT AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
