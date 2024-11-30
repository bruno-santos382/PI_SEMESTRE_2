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
-- Estrutura para tabela `categoria_produto`
--

CREATE TABLE `categoria_produto` (
  `IdCategoria` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `Nome` VARCHAR(40) DEFAULT NULL,
  `Descricao` TEXT DEFAULT NULL,
  `Pagina` VARCHAR(40) DEFAULT NULL,
  `DataCriacao` DATETIME DEFAULT CURRENT_TIMESTAMP(),
  `DataExclusao` DATETIME DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `categoria_produto` (`IdCategoria`, `Nome`, `Pagina`)
VALUES 
    (1, 'Carnes', 'acougue'),
    (2, 'Verduras', 'hortifruti'),
    (3, 'Frutas', 'hortifruti'),
    (4, 'Legumes', 'hortifruti'),
    (5, 'Bebidas', 'bebidas'),
    (6, 'Limpeza da Casa', 'limpeza'),
    (7, 'Limpeza da Cozinha', 'limpeza'),
    (8, 'Lavagem de Roupas', 'limpeza'),
    (9, 'Higiene Pessoal', 'limpeza'),
    (10, 'Pães', 'padaria'),
    (11, 'Doces', 'padaria'),
    (12, 'Salgados', 'padaria'),
    (13, 'Grãos e Cereais', 'mercenaria');

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
  IdCategoria INT DEFAULT NULL,
  `IdImagem` INT(11) DEFAULT NULL,
  CONSTRAINT `fk_produtos_imagens` FOREIGN KEY (`IdImagem`) REFERENCES `imagens`(`IdImagem`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_produto_categoria` FOREIGN KEY (`IdCategoria`) REFERENCES `categoria_produto`(`IdCategoria`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


-- Inserindo dados para a tabela `produtos`
INSERT INTO produtos (IdProduto, Nome, Preco, Marca, Estoque, IdCategoria, IdImagem) VALUES 
    (1, 'Abacaxi', 5.00, 'Marca A', 50, 3, 1),          -- Categoria: Frutas
    (2, 'Abóbora', 4.00, 'Marca B', 30, 4, 2),           -- Categoria: Legumes
    (3, 'Alface', 3.00, 'Marca C', 100, 2, 3),           -- Categoria: Verduras
    (4, 'Banana', 3.00, 'Marca D', 60, 3, 4),            -- Categoria: Frutas
    (5, 'Batata', 2.50, 'Marca E', 200, 4, 5),           -- Categoria: Legumes
    (6, 'Batata Doce', 3.00, 'Marca F', 150, 4, 6),      -- Categoria: Legumes
    (7, 'Brócolis', 5.00, 'Marca G', 80, 2, 7),          -- Categoria: Verduras
    (8, 'Carne Moída', 30.00, 'Marca H', 50, 1, 8),      -- Categoria: Carnes
    (9, 'Cebolinha', 1.80, 'Marca I', 100, 2, 9),        -- Categoria: Verduras
    (10, 'Cenoura', 1.50, 'Marca J', 180, 4, 11),        -- Categoria: Legumes
    (11, 'Cereja', 10.00, 'Marca K', 40, 3, 12),         -- Categoria: Frutas
    (12, 'Couve', 3.50, 'Marca L', 120, 2, 13),          -- Categoria: Verduras
    (13, 'Escarola', 4.00, 'Marca M', 90, 2, 14),        -- Categoria: Verduras
    (14, 'Espinafre', 5.00, 'Marca N', 60, 2, 15),       -- Categoria: Verduras
    (15, 'Frango', 25.00, 'Marca O', 70, 1, 16),         -- Categoria: Carnes
    (16, 'Kiwi', 6.00, 'Marca P', 40, 3, 17),            -- Categoria: Frutas
    (17, 'Maçã', 3.50, 'Marca Q', 80, 3, 21),            -- Categoria: Frutas
    (18, 'Mamão', 4.50, 'Marca R', 60, 3, 18),           -- Categoria: Frutas
    (19, 'Mandioca', 5.00, 'Marca S', 50, 4, 19),        -- Categoria: Legumes
    (20, 'Manga', 6.00, 'Marca T', 40, 3, 20),           -- Categoria: Frutas
    (21, 'Melancia', 8.00, 'Marca U', 30, 3, 22),        -- Categoria: Frutas
    (22, 'Morangos', 10.00, 'Marca V', 20, 3, 23),       -- Categoria: Frutas
    (23, 'Pepino', 2.00, 'Marca W', 150, 4, 24),         -- Categoria: Legumes
    (24, 'Pimentão', 3.50, 'Marca X', 120, 4, 25),       -- Categoria: Legumes
    (25, 'Rabanete', 2.50, 'Marca Y', 80, 4, 26),        -- Categoria: Legumes
    (26, 'Rúcula', 3.00, 'Marca Z', 100, 2, 27),         -- Categoria: Verduras
    (27, 'Salsinha', 1.20, 'Marca AA', 200, 2, 28),      -- Categoria: Verduras
    (28, 'Suco de Laranja', 5.00, 'Marca AB', 40, 5, 29), -- Categoria: Bebidas
    (29, 'Tomate', 3.00, 'Marca AC', 150, 4, 30),        -- Categoria: Legumes
    (30, 'Uva Francesa', 10.00, 'Marca AD', 30, 3, 31),  -- Categoria: Frutas
    (31, 'Uva Verde', 8.00, 'Marca AE', 40, 3, 32);      -- Categoria: Frutas



--
-- Estrutura para tabela `promoções`
--

CREATE TABLE `promocoes` (
  `IdPromocao` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `IdProduto` INT NOT NULL,
  `DataInicio` DATE NOT NULL,
  `DataFim` DATE NOT NULL,
  `Desconto` DECIMAL(5,2) NOT NULL,
  `DataCriacao` DATETIME DEFAULT CURRENT_TIMESTAMP(),
  `DataExclusao` DATETIME DEFAULT NULL,
  CONSTRAINT `fk_promocoes_produto` FOREIGN KEY (`IdProduto`) REFERENCES `produtos`(`IdProduto`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


-- Inserindo promoções
INSERT INTO promocoes (IdProduto, DataInicio, DataFim, Desconto) VALUES
    (1, '2024-01-01', '2025-12-31', 10.00),  -- Abacaxi com 10% de desconto
    (2, '2024-01-01', '2025-12-31', 15.00),  -- Abóbora com 15% de desconto
    (3, '2024-01-01', '2025-12-31', 5.00),   -- Alface com 5% de desconto
    (4, '2024-01-01', '2025-12-31', 20.00),  -- Banana com 20% de desconto
    (5, '2024-01-01', '2025-12-31', 10.00),  -- Batata com 10% de desconto
    (6, '2024-01-01', '2025-12-31', 8.00),   -- Batata Doce com 8% de desconto
    (7, '2024-01-01', '2025-12-31', 12.00),  -- Brócolis com 12% de desconto
    (8, '2024-01-01', '2025-12-31', 25.00),  -- Carne Moída com 25% de desconto
    (9, '2024-01-01', '2025-12-31', 5.00),   -- Cebolinha com 5% de desconto
    (10, '2024-12-01', '2024-12-15', 18.00),  -- Cenoura com 18% de desconto
    (11, '2024-12-06', '2024-12-20', 10.00),  -- Cereja com 10% de desconto
    (12, '2024-12-03', '2024-12-18', 8.00),   -- Couve com 8% de desconto
    (13, '2024-12-10', '2024-12-24', 5.00),   -- Escarola com 5% de desconto
    (14, '2024-12-05', '2024-12-22', 15.00),  -- Espinafre com 15% de desconto
    (15, '2024-12-09', '2024-12-21', 20.00),  -- Frango com 20% de desconto
    (16, '2024-12-13', '2024-12-27', 10.00),  -- Kiwi com 10% de desconto
    (17, '2024-12-02', '2024-12-17', 12.00),  -- Maçã com 12% de desconto
    (18, '2024-12-11', '2024-12-28', 5.00),   -- Mamão com 5% de desconto
    (19, '2024-12-07', '2024-12-23', 8.00),   -- Mandioca com 8% de desconto
    (20, '2024-12-04', '2024-12-19', 12.00),  -- Manga com 12% de desconto
    (21, '2024-12-10', '2024-12-24', 5.00),   -- Melancia com 5% de desconto
    (22, '2024-12-15', '2024-12-30', 15.00),  -- Morangos com 15% de desconto
    (23, '2024-12-09', '2024-12-22', 20.00),  -- Pepino com 20% de desconto
    (24, '2024-12-12', '2024-12-27', 10.00),  -- Pimentão com 10% de desconto
    (25, '2024-12-08', '2024-12-21', 12.00),  -- Rabanete com 12% de desconto
    (26, '2024-12-01', '2024-12-15', 5.00),   -- Rúcula com 5% de desconto
    (27, '2024-12-06', '2024-12-18', 10.00),  -- Salsinha com 10% de desconto
    (28, '2024-12-05', '2024-12-19', 12.00),  -- Suco de Laranja com 12% de desconto
    (29, '2024-12-11', '2024-12-25', 15.00),  -- Tomate com 15% de desconto
    (30, '2024-12-03', '2024-12-16', 8.00),   -- Uva Francesa com 8% de desconto
    (31, '2024-12-07', '2024-12-20', 10.00);   -- Uva Verde com 10% de desconto


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
CREATE TRIGGER TRG_SET_PRODUTO_IMAGEM_NULL_ON_EXCLUSAO
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


DELIMITER $$

-- Descrição: Este trigger é responsável por definir o campo DataExclusao nas promoções
-- quando a DataExclusao de um produto associado a uma promoção for definida.
CREATE TRIGGER TRG_EXCLUIR_PROMOCAO_ON_PRODUTO_EXCLUIDO
BEFORE UPDATE ON produtos
FOR EACH ROW
BEGIN
  -- Verifica se a DataExclusao do produto foi alterada para não nula
  IF NEW.DataExclusao IS NOT NULL AND OLD.DataExclusao IS NULL THEN
    -- Atualiza o campo DataExclusao nas promoções associadas ao produto
    UPDATE promocoes
    SET DataExclusao = CURRENT_TIMESTAMP()
    WHERE IdProduto = OLD.IdProduto AND DataExclusao IS NULL;
  END IF;
END $$

DELIMITER ;


DELIMITER $$

-- Descrição: Este trigger é responsável por definir o campo IdCategoria como NULL em todos os produtos
-- que estão referenciando uma categoria quando a DataExclusao da categoria é definida.
CREATE TRIGGER TRG_SET_PRODUTO_CATEGORIA_NULL_ON_EXCLUSAO
BEFORE UPDATE ON categoria_produto
FOR EACH ROW
BEGIN
  -- Verifica se a data de exclusão foi alterada para não nula
  IF NEW.DataExclusao IS NOT NULL AND OLD.DataExclusao IS NULL THEN
    -- Define o campo IdCategoria como NULL nos produtos que referenciam a categoria excluída
    UPDATE produtos
    SET IdCategoria = NULL
    WHERE IdCategoria = OLD.IdCategoria;
  END IF;
END $$

DELIMITER ;


DELIMITER $$

-- Função para calcular o preço com desconto
-- Parâmetros:
--   preco: O preço original do produto.
--   desconto: O percentual de desconto aplicado.
-- Retorna o valor final do preço após o desconto, arredondado para 2 casas decimais.
CREATE FUNCTION FN_CALCULAR_PRECO_COM_DESCONTO(preco DECIMAL(10,2), desconto DECIMAL(5,2))
RETURNS DECIMAL(10,2)
DETERMINISTIC
BEGIN
    -- Verifica se algum parâmetro é NULL e retorna NULL se for o caso
    IF preco IS NULL OR desconto IS NULL THEN
        RETURN NULL;
    END IF;

    -- Calcula o preço com o desconto e arredonda para 2 casas decimais
    RETURN ROUND(preco - (preco * (desconto / 100)), 2);
END $$

DELIMITER ;


DELIMITER $$

-- Criação da view para promoções dos produtos
CREATE VIEW VW_PROMOCOES_PRODUTO_ATIVAS AS
SELECT 
    p.IdProduto, 
    p.Nome, 
    p.Marca,
    pr.IdPromocao,
    pr.DataInicio, 
    pr.DataFim, 
    pr.Desconto,
    p.Preco AS PrecoAntigo,
    FN_CALCULAR_PRECO_COM_DESCONTO(p.Preco, pr.Desconto) AS PrecoComDesconto,
    img.caminho AS Imagem,
    CASE 
        WHEN pr.DataFim < CURDATE() THEN 'Expirado'
        ELSE 'Ativo'
    END AS Status
FROM promocoes pr
JOIN produtos p ON pr.IdProduto = p.IdProduto
LEFT JOIN imagens img ON img.idimagem = p.idimagem
WHERE pr.dataexclusao IS NULL

$$
DELIMITER ;


DELIMITER $$

-- Criação de view para produtos ativos
CREATE VIEW VW_PRODUTOS_ATIVOS AS
SELECT 
    p.*, 
    img.caminho AS Imagem,
    cat.nome AS Categoria,
    FN_CALCULAR_PRECO_COM_DESCONTO(p.Preco, pr.Desconto) AS PrecoComDesconto
FROM produtos p
LEFT JOIN imagens img ON img.idimagem = p.idimagem
LEFT JOIN categoria_produto cat ON cat.idcategoria = p.idcategoria
LEFT JOIN promocoes pr 
  ON pr.idproduto = p.idproduto 
  AND pr.dataexclusao IS NULL
  AND CURDATE() BETWEEN pr.datainicio AND pr.datafim
WHERE p.dataexclusao IS NULL

$$
DELIMITER ;


/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
