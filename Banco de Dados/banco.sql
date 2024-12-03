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
SET time_zone = 'America/Sao_Paulo';

CREATE DATABASE IF NOT EXISTS mercado;
USE mercado;

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `mercado`
--


-- --------------------------------------------------------

--
-- Estrutura tabela de usuários
--

CREATE TABLE `usuarios` (
  IdUsuario INT AUTO_INCREMENT PRIMARY KEY,
  Usuario VARCHAR(50) NOT NULL,
  Senha VARCHAR(255) NOT NULL,
  DataCriacao DATETIME DEFAULT CURRENT_TIMESTAMP(),
  DataExclusao DATETIME DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Usuario administrador padrão, usuario: admin, senha: admin
INSERT INTO usuarios (IdUsuario, Usuario, Senha) VALUES (1, 'admin', '$2y$10$I5cV9q6YCgQkUMPA1e83seoNnpAvlwO4oil84KMACnLfFWPLswnkO');

-- Usuários dos clientes
INSERT INTO usuarios (IdUsuario, Usuario, Senha) VALUES
(2, 'joao.silva', '$2y$10$rb8Akhc4NYSl1EZrv3BCNunNewOjM6W.UKBIoeUftwNgNs2LGvaRO'), -- senha: joao123
(3, 'maria.oliveira', '$2y$10$NTp9CI39LaCvheFVXubL.OG.epk06ugP0Dchdepr7w24O6OAuOvTG'), -- senha: maria123
(4, 'carlos.souza', '$2y$10$/tbYKKpuaQ943MErhGir5.VMidwEnb13oljqRGeJgkvXJMUiGr5G.'), -- senha: carlos123
(5, 'ana.lima', '$2y$10$N560.SNe27hjDZR3.3TE3.ybbu6mdhgmcF.9xXDnm9Wby4KpfGd/K'); -- senha: ana123



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

-- Permissão do usuário 'admin'
INSERT INTO permissao_usuarios (IdUsuario, Permissao) VALUES (1, 'acesso_admin');


--
-- Estrutura para tabela `pessoas`
--

CREATE TABLE `pessoas` (
  `IdPessoa` int(11) NOT NULL AUTO_INCREMENT,
  `Nome` varchar(150) NOT NULL,
  Email VARCHAR(100) NOT NULL,
  Telefone VARCHAR(20) NOT NULL,
  DataCriacao DATETIME DEFAULT CURRENT_TIMESTAMP(),
  `Tipo` ENUM('funcionario', 'cliente') NOT NULL,
  DataExclusao DATETIME DEFAULT NULL,
  IdUsuario INT NOT NULL,
  PRIMARY KEY (IdPessoa),
  FOREIGN KEY (IdUsuario) REFERENCES usuarios(IdUsuario)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


--
-- Inserir pessoas na tabela `pessoas`
--

INSERT INTO pessoas (IdPessoa, Nome, Email, Telefone, IdUsuario, Tipo) VALUES
(2, 'João Silva', 'joao@email.com', '19912345671', 2, 'cliente'),
(3, 'Maria Oliveira', 'maria@email.com', '19912345672', 3, 'cliente'),
(4, 'Carlos Souza', 'carlos@email.com', '19912345673', 4, 'cliente'),
(5, 'Ana Lima', 'ana@email.com', '19912345674', 5, 'cliente');


--
-- Inserir funcionários na tabela `pessoas`
--

INSERT INTO pessoas (IdPessoa, Nome, Email, Telefone, IdUsuario, Tipo) VALUES
(1, 'Administrador', 'admin@exemplo.com', '19912345678', 1, 'funcionario');




-- --------------------------------------------------------


-- Estrutura tabela de imagens com AUTO_INCREMENT para IdImagem
CREATE TABLE imagens (
  IdImagem INT AUTO_INCREMENT PRIMARY KEY,  -- Definindo o ID para auto incremento
  NomeImagem VARCHAR(255) DEFAULT NULL,
  Caminho VARCHAR(255) NOT NULL,
  DataCriacao DATETIME DEFAULT CURRENT_TIMESTAMP(),
  DataExclusao DATETIME DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Inserir imagens dos produtos
INSERT INTO imagens (IdImagem, NomeImagem, Caminho) VALUES
    (1, 'picanha.webp', 'static/img/upload/picanha.webp'),
    (2, 'alcatra.jpg', 'static/img/upload/alcatra.jpg'),
    (3, 'contrafile.jpg', 'static/img/upload/contrafile.jpg'),
    (4, 'maminha.jpg', 'static/img/upload/maminha.jpg'),
    (5, 'coxaomole.jpg', 'static/img/upload/coxaomole.jpg'),
    (6, 'carnemoida.webp', 'static/img/upload/carnemoida.webp'),
    (7, 'salmao.jpg', 'static/img/upload/salmao.jpg'),
    (8, 'tilapia.jpg', 'static/img/upload/tilapia.jpg'),
    (9, 'camarao.webp', 'static/img/upload/camarao.webp'),
    (10, 'bacalhau.png', 'static/img/upload/bacalhau.png'),
    (11, 'frangocru.webp', 'static/img/upload/frangocru.webp'),
    (12, 'coxafrango.jpg', 'static/img/upload/coxafrango.jpg'),
    (13, 'peitodefrango.jpg', 'static/img/upload/peitodefrango.jpg'),
    (14, 'sobrecoxa.png', 'static/img/upload/sobrecoxa.png'),
    (15, 'asadefrango.webp', 'static/img/upload/asadefrango.webp'),
    (16, 'coracaogalinha.webp', 'static/img/upload/coracaogalinha.webp'),
    (17, 'coca (2).webp', 'static/img/upload/coca (2).webp'),
    (18, 'pepsi.jpg', 'static/img/upload/pepsi.jpg'),
    (19, 'fanta.webp', 'static/img/upload/fanta.webp'),
    (20, 'sprite.webp', 'static/img/upload/sprite.webp'),
    (21, 'moggi.jpg', 'static/img/upload/moggi.jpg'),
    (22, 'fantauva.webp', 'static/img/upload/fantauva.webp'),
    (23, 'suco.jpg', 'static/img/upload/suco.jpg'),
    (24, 'sucouva.webp', 'static/img/upload/sucouva.webp'),
    (25, 'goiaba.png', 'static/img/upload/goiaba.png'),
    (26, 'abacaxi.webp', 'static/img/upload/abacaxi.webp'),
    (27, 'maracuja.webp', 'static/img/upload/maracuja.webp'),
    (28, 'gas.webp', 'static/img/upload/gas.webp'),
    (29, 'agua.webp', 'static/img/upload/agua.webp'),
    (30, 'alface.webp', 'static/img/upload/alface.webp'),
    (31, 'couve.png', 'static/img/upload/couve.png'),
    (32, 'espinafre[1]-1000x1000.jpg', 'static/img/upload/espinafre[1]-1000x1000.jpg'),
    (33, 'rucula.png', 'static/img/upload/rucula.png'),
    (34, 'rabanete.png', 'static/img/upload/rabanete.png'),
    (35, 'salcinha.jpg', 'static/img/upload/salcinha.jpg'),
    (36, 'cebolinha.webp', 'static/img/upload/cebolinha.webp'),
    (37, 'brocolis.png', 'static/img/upload/brocolis.png'),
    (38, 'escarola.webp', 'static/img/upload/escarola.webp'),
    (39, 'banana.png', 'static/img/upload/banana.png'),
    (40, 'kiwi.png', 'static/img/upload/kiwi.png'),
    (41, 'maça.jpg', 'static/img/upload/maça.jpg'),
    (42, 'cereja.webp', 'static/img/upload/cereja.webp'),
    (43, 'melancia.png', 'static/img/upload/melancia.png'),
    (44, 'uvafrancesa.webp', 'static/img/upload/uvafrancesa.webp'),
    (45, 'uvaverde.jpg', 'static/img/upload/uvaverde.jpg'),
    (46, 'manga.webp', 'static/img/upload/manga.webp'),
    (47, 'abacaxi.jpg', 'static/img/upload/abacaxi.jpg'),
    (48, 'morangos1.jpg', 'static/img/upload/morangos1.jpg'),
    (49, 'mamao.webp', 'static/img/upload/mamao.webp'),
    (50, 'cenoura.png', 'static/img/upload/cenoura.png'),
    (51, 'Batata.png', 'static/img/upload/Batata.png'),
    (52, 'tomate.jpg', 'static/img/upload/tomate.jpg'),
    (53, 'pepino.webp', 'static/img/upload/pepino.webp'),
    (54, 'Mandioca.webp', 'static/img/upload/Mandioca.webp'),
    (55, 'batatadoce.webp', 'static/img/upload/batatadoce.webp'),
    (56, 'Pimentao.png', 'static/img/upload/Pimentao.png'),
    (57, 'abobora.png', 'static/img/upload/abobora.png'),
    (58, 'sabonete.png', 'static/img/upload/sabonete.png'), 
    (59, 'shampo.webp', 'static/img/upload/shampo.webp'), 
    (60, 'condicionador.webp', 'static/img/upload/condicionador.webp'), 
    (61, 'oral.jpg', 'static/img/upload/oral.jpg'), 
    (62, 'papel.webp', 'static/img/upload/papel.webp'), 
    (63, 'desi.webp', 'static/img/upload/desi.webp'), 
    (64, 'dete.webp', 'static/img/upload/dete.webp'), 
    (65, 'limpa.webp', 'static/img/upload/limpa.webp'), 
    (66, 'omo.webp', 'static/img/upload/omo.webp'), 
    (67,'aguas.webp','static/img/upload/aguas.webp'),
    (68,'pá.jpg','static/img/upload/pá.jpg'),
    (69,'vassoura.webp','static/img/upload/vassoura.webp'),
    (70,'rodo.webp','static/img/upload/rodo.webp'),
    (71,'esp.webp','static/img/upload/esp.webp'),
    (72, 'arroz.png', 'static/img/upload/arroz.png'), 
    (73, 'feijao.png', 'static/img/upload/feijao.png'), 
    (74, 'açucar.webp', 'static/img/upload/açucar.webp'), 
    (75, 'sal.webp', 'static/img/upload/sal.webp'), 
    (76, 'macarrao.jpg', 'static/img/upload/macarrao.jpg'), 
    (77,'oleo.webp','static/img/upload/oleo.webp'),
    (78,'moça.webp','static/img/upload/moça.webp'),
    (79,'cremeleite.webp','static/img/upload/cremeleite.webp'),
    (80,'leiteintegral.jpg','static/img/upload/leiteintegral.jpg'),
    (81,'leitedesnatado.webp','static/img/upload/leitedesnatado.webp'),
    (82,'po.webp','static/img/upload/po.webp'),
    (83,'pocafe.jpg','static/img/upload/pocafe.jpg'),
    (84,'farinhatrigo.jpg','static/img/upload/farinhatrigo.jpg'),
    (85,'rosca.webp','static/img/upload/rosca.webp'),
    (86,'mandioca (2).webp','static/img/upload/mandioca (2).webp'),
    (87,'milho.webp','static/img/upload/milho.webp'),
    (88, 'paof.webp', 'static/img/upload/paof.webp'), 
    (89, 'paoi.webp', 'static/img/upload/paoi.webp'), 
    (90, 'paod.webp', 'static/img/upload/paod.webp'), 
    (91, 'paol.png', 'static/img/upload/paol.png'), 
    (92, 'boloc.jpg', 'static/img/upload/boloc.jpg'), 
    (93,'bolol.jpg','static/img/upload/bolol.jpg'),
    (94,'bolom.jpg','static/img/upload/bolom.jpg'),
    (95,'sonho.jpg','static/img/upload/sonho.jpg'),
    (96,'pudim.webp','static/img/upload/pudim.webp'),
    (97,'queijom.jpg','static/img/upload/queijom.jpg'),
    (98,'presunto.jpg','static/img/upload/presunto.jpg'),
    (99,'salame.webp','static/img/upload/salame.webp'),
    (100,'queijop.jpg','static/img/upload/queijop.jpg'),
    (101,'mortandela.webp','static/img/upload/mortandela.webp');





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

INSERT INTO `categoria_produto` (`IdCategoria`, `Nome`, `Descricao`, `Pagina`) VALUES 
    (1, 'Carnes Vermelhas', 'Inclui cortes de carne bovina, como picanha, alcatra e filé mignon.', 'acougue'),
    (2, 'Verduras', 'Variedades de vegetais frescos, como alface, couve e rúcula.', 'hortifruti'),
    (3, 'Frutas', 'Diversas frutas frescas, incluindo banana, maçã e kiwi.', 'hortifruti'),
    (4, 'Legumes', 'Inclui legumes frescos como cenoura, batata e tomate.', 'hortifruti'),
    (5, 'Refrigerantes', 'Bebidas carbonatadas em diversas marcas e sabores.', 'bebidas'),
    (19, 'Higiene Pessoal', 'Produtos para cuidados pessoais.', 'limpeza'),
    (20, 'Higiene de Casa', 'Produtos para limpeza e desinfecção da casa.', 'limpeza'),
    (21, 'Utensílios', 'Utensílios para limpeza e organização.', 'limpeza'),
    (14, 'Peixaria', 'Variedades de peixes frescos e congelados.', 'acougue'),
    (15, 'Aves', 'Cortes de carne de frango e outras aves.', 'acougue'),
    (17, 'Sucos', 'Sucos naturais e industrializados em diversas opções.', 'bebidas'),
    (18, 'Águas', 'Águas minerais e com gás em diferentes embalagens.', 'bebidas'),
    (22, 'Essências', 'Produtos essenciais como grãos e açúcar.', 'mercearia'),
    (23, 'Derivados', 'Laticínios e produtos derivados do leite.', 'mercearia'),
    (24, 'Grãos e Farinhas', 'Grãos e farinhas para uso culinário.', 'mercearia'),
    (25, 'Pães', 'Variedade de pães frescos.', 'padaria'),
    (26, 'Doces', 'Bolos e doces variados.', 'padaria'),
    (27, 'Frios', 'Queijos e embutidos.', 'padaria');


--
-- Estrutura para tabela `produtos`
--

CREATE TABLE `produtos` (
  `IdProduto` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `Nome` VARCHAR(40) DEFAULT NULL,
  `Preco` DECIMAL(10,2) DEFAULT NULL,
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

-- Categoria: Carnes (IdCategoria = 1)
INSERT INTO `produtos` (`IdProduto`, `Nome`, `Preco`, `Marca`, `IdCategoria`, `IdImagem`, `Estoque`) 
VALUES
    (1, 'Picanha', 49.99, 'Picanha bovina - 1kg', 1, 1, 99),
    (2, 'Alcatra', 39.99, 'Alcatra bovina - 1kg', 1, 2, 99),
    (3, 'Contrafilé', 42.99, 'Contrafíle bovino - 1kg', 1, 3, 99),
    (4, 'Maminha', 36.99, 'Maminha bovina - 1kg', 1, 4, 99),
    (5, 'Coxão Mole', 37.99, 'Coxão Mole bovino - 1kg', 1, 5, 99),
    (6, 'Carne Moída', 24.99, 'Carne Moída - 1kg', 1, 6, 99);

-- Categoria: Peixaria (IdCategoria = 14)
INSERT INTO `produtos` (`IdProduto`, `Nome`, `Preco`, `Marca`, `IdCategoria`, `IdImagem`, `Estoque`) 
VALUES
    (7, 'Salmão', 89.99, 'Salmão fresco - 1kg', 14, 7, 99),
    (8, 'Tilápia', 42.99, 'Filé de tilápia - 1kg', 14, 8, 99),
    (9, 'Camarão', 74.99, 'Camarão médio - 1kg', 14, 9, 99),
    (10, 'Bacalhau', 59.99, 'Bacalhau desfiado - 1kg', 14, 10, 99);

-- Categoria: Aves (IdCategoria = 15)
INSERT INTO `produtos` (`IdProduto`, `Nome`, `Preco`, `Marca`, `IdCategoria`, `IdImagem`, `Estoque`) 
VALUES
    (11, 'Frango', 12.99, 'Frango desossado - 1kg', 15, 11, 99),
    (12, 'Coxa de frango', 10.99, 'Coxa de frango - 1kg', 15, 12, 99),
    (13, 'Peito de Frango', 15.99, 'Peito de Frango - 1kg', 15, 13, 99),
    (14, 'Sobrecoxa de Frango', 11.99, 'Sobrecoxa de Frango - 1kg', 15, 14, 99),
    (15, 'Asa de Frango', 9.99, 'Asa de Frango - 1kg', 15, 15, 99),
    (16, 'Coração de Galinha', 8.99, 'Coração de Galinha - 500g', 15, 16, 99);


    -- Categoria: Refrigerantes (IdCategoria = 5)
INSERT INTO `produtos` (`IdProduto`, `Nome`, `Preco`, `Marca`, `IdCategoria`, `IdImagem`, `Estoque`) 
VALUES
    (17, 'Coca-Cola', 7.99, 'Coca-Cola 2L', 5, 17, 99),
    (18, 'Pepsi', 6.99, 'Pepsi 2L', 5, 18, 99),
    (19, 'Fanta Laranja', 6.49, 'Fanta Laranja 2L', 5, 19, 99),
    (20, 'Sprite', 7.49, 'Sprite 2L', 5, 20, 99),
    (21, 'Mogi Abacaxi', 4.49, 'Mogi Abacaxi 2L', 5, 21, 99),
    (22, 'Fanta Uva', 6.49, 'Fanta Uva 2L', 5, 22, 99);

-- Categoria: Sucos (IdCategoria = 17)
INSERT INTO `produtos` (`IdProduto`, `Nome`, `Preco`, `Marca`, `IdCategoria`, `IdImagem`, `Estoque`) 
VALUES
    (23, 'Suco de Laranja', 5.99, 'Suco Natural 1L', 17, 23, 99),
    (24, 'Suco de Uva', 5.99, 'Suco de Uva Integral 1L', 17, 24, 99),
    (25, 'Suco de Goiaba', 6.49, 'Suco de Goiaba Valle 1L', 17, 25, 99),
    (26, 'Suco de Abacaxi', 6.49, 'Suco de Abacaxi Valle 1L', 17, 26, 99),
    (27, 'Suco de Maracuja', 8.49, 'Suco de Maracuja 1L', 17, 27, 99);

-- Categoria: Águas (IdCategoria = 18)
INSERT INTO `produtos` (`IdProduto`, `Nome`, `Preco`, `Marca`, `IdCategoria`, `IdImagem`, `Estoque`) 
VALUES
    (28, 'Água com Gás', 2.99, 'Água com Gás 500ml', 18, 28, 99),
    (29, 'Água Mineral', 1.99, 'Água Mineral 500ml', 18, 29, 99);


-- Inserindo Produtos para Verduras
INSERT INTO `produtos` (`IdProduto`, `Nome`, `Preco`, `Marca`, `IdCategoria`, `IdImagem`, `Estoque`) VALUES
(30, 'Alface', 2.99, 'Alface fresca - Unidade', 2, 30, 100),  -- Alface fresca
(31, 'Couve', 3.99, 'Couve fresca - Maço', 2, 31, 100),      -- Couve fresca
(32, 'Espinafre', 4.99, 'Espinafre fresco - Maço', 2, 32, 100), -- Espinafre fresco
(33, 'Rúcula', 3.99, 'Rúcula fresca - Maço', 2, 33, 100),    -- Rúcula fresca
(34, 'Rabanete', 4.99, 'Rabanete fresco - Maço', 2, 34, 100), -- Rabanete fresco
(35, 'Salcinha', 1.99, 'Salcinha - Maço', 2, 35, 100),       -- Salcinha
(36, 'Cebolinha', 2.99, 'Cebolinha fresca - Maço', 2, 36, 100), -- Cebolinha fresca
(37, 'Brócolis', 4.99, 'Brócolis fresco - Maço', 2, 37 ,100), -- Brócolis fresco
(38,'Escarola',4.99,'Escarola fresca - Maço' ,2 ,38 ,100);     -- Escarola fresca

-- Inserindo Produtos para Frutas
INSERT INTO `produtos` (`IdProduto`, `Nome`, `Preco`, `Marca`, `IdCategoria`, `IdImagem`, `Estoque`) VALUES
(39,'Banana' ,6.99,'Banana prata - 1kg' ,3 ,39 ,100),         -- Banana prata
(40,'Kiwi' ,6.99,'Kiwi-1kg' ,3 ,40 ,100),                    -- Kiwi
(41,'Maçã' ,6.99,'Maçã argentina - 1kg' ,3 ,41 ,100),       -- Maçã argentina
(42,'Cereja' ,2.99,'Cereja - 1kg' ,3 ,42 ,100),              -- Cereja
(43,'Melancia' ,14.99,'Melancia - 1kg' ,3 ,43 ,100),         -- Melancia
(44,'Uva Francesa' ,6.99,'Uva Cacho-1kg' ,3 ,44 ,100),       -- Uva Francesa
(45,'Uva Verde' ,11.98,'Uva Verde S/Semente Bandeja-500gr' ,3 ,45 ,100), -- Uva Verde
(46,'Manga' ,5.00,'Manga Tommy-1kg' ,3 ,46 ,100),            -- Manga Tommy
(47,'Abacaxi' ,6.99,'Abacaxi Unidade-1kg' ,3 ,47 ,100),      -- Abacaxi Unidade
(48,'Morango' ,6.99,'Morango fresco-500g' ,3 ,48 ,100),      -- Morango fresco
(49,'Mamão' ,12.99,'Mamão Fresco-5kg' ,3 ,49 ,100);           -- Mamão Fresco

-- Inserindo Produtos para Legumes
INSERT INTO `produtos` (`IdProduto`, `Nome`, `Preco`, `Marca`, `IdCategoria`, `IdImagem`, `Estoque`) VALUES
(50,'Cenoura',4.99,'Cenoura fresca - 1kg',4,50,100),         -- Cenoura fresca
(51,'Batata',7.99,'Batata fresca - 1kg',4,51,100),           -- Batata fresca
(52,'Tomate',6.99,'Tomate fresco - 1kg',4,52,100),           -- Tomate fresco
(53,'Pepino',6.99,'Pepino Fresco - 1kg',4,53,100),           -- Pepino Fresco
(54,'Mandioca',3.99,'Mandioca fresca - 1kg',4,54,100),       -- Mandioca fresca
(55,'Batata Doce',4.99,'Batata Doce - 1kg',4,55,100),        -- Batata Doce
(56,'Pimentão',4.99,'Pimentão fresco - UNI',4,56,100),       -- Pimentão fresco
(57,'Abobrinha',4.99,'Abobrinha - 1kg',4,57,100);            -- Abobrinha


-- Inserindo Produtos para Higiene Pessoal
INSERT INTO `produtos` (`IdProduto`, `Nome`, `Preco`, `Marca`, `IdCategoria`, `IdImagem`, `Estoque`) VALUES
(58, 'Sabonete', 2.99, 'Sabonete hidratante - unidade', 19, 58, 100),  -- Sabonete
(59, 'Shampoo', 12.90, 'Shampoo Dove - 400ml', 19, 59, 100),         -- Shampoo
(60, 'Condicionador', 15.49, 'Condicionador Dove - 400ml', 19, 60, 100), -- Condicionador
(61, 'Creme Dental', 7.49, 'Creme dental branqueador - 90g', 19, 61, 100), -- Creme Dental
(62, 'Papel Higiênico', 11.49, 'Papel Higiênico - 12uni', 19, 62, 100); -- Papel Higiênico

-- Inserindo Produtos para Higiene de Casa
INSERT INTO `produtos` (`IdProduto`, `Nome`, `Preco`, `Marca`, `IdCategoria`, `IdImagem`, `Estoque`) VALUES
(63, 'Desinfetante', 9.90, 'Desinfetante perfumado - 2L', 20, 63, 100), -- Desinfetante
(64, 'Detergente', 3.49, 'Detergente neutro - 500ml', 20, 64, 100),     -- Detergente
(65, 'Limpador Multiuso', 5.99, 'Limpador multiuso - 500ml', 20, 65, 100), -- Limpador Multiuso
(66, 'Sabão em Pó', 12.99, 'Sabão em Pó Homo - 500gr', 20, 66, 100),     -- Sabão em Pó
(67,'Água Sanitária',4.99,'Água Sanitária - 1L',20 ,67 ,100);            -- Água Sanitária

-- Inserindo Produtos para Utensílios
INSERT INTO `produtos` (`IdProduto`, `Nome`, `Preco`, `Marca`, `IdCategoria`, `IdImagem`, `Estoque`) VALUES
(68,'Pá de Lixo',9.99,'Pá de Lixo - Unidade',21 ,68 ,100),             -- Pá de Lixo
(69,'Vassoura',14.90,'Vassoura multiuso - Unidade',21 ,69 ,100),      -- Vassoura
(70,'Rodo',12.49,'Rodo de alumínio - Unidade',21 ,70 ,100),           -- Rodo
(71,'Esponja',4.49,'Esponja Boombril - pacote com várias unidades',21 ,71 ,100); -- Esponja


-- Inserindo Produtos para Essências
INSERT INTO `produtos` (`IdProduto`, `Nome`, `Preco`, `Marca`, `IdCategoria`, `IdImagem`, `Estoque`) VALUES
(72, 'Arroz', 25.99, 'Arroz tipo 1 - 5kg', 22, 72, 100),  -- Arroz
(73, 'Feijão', 7.99, 'Feijão carioca - 1kg', 22, 73, 100), -- Feijão
(74, 'Açúcar', 3.99, 'Açúcar refinado - 1kg', 22, 74, 100), -- Açúcar
(75, 'Sal', 2.50, 'Sal refinado - 1kg', 22, 75, 100), -- Sal
(76, 'Macarrão', 4.99, 'Macarrão espaguete - 500g', 22, 76, 100), -- Macarrão
(77, 'Óleo de Soja', 8.99, 'Óleo de soja - 900ml', 22, 77, 100); -- Óleo de Soja

-- Inserindo Produtos para Derivados
INSERT INTO `produtos` (`IdProduto`, `Nome`, `Preco`, `Marca`, `IdCategoria`, `IdImagem`, `Estoque`) VALUES
(78, 'Leite Condensado', 5.99, 'Leite condensado - 395g', 23, 78, 100), -- Leite Condensado
(79, 'Creme de Leite', 3.99, 'Creme de leite - 200g', 23, 79, 100), -- Creme de Leite
(80, 'Leite Integral', 4.49, 'Leite integral - 1L', 23, 80, 100), -- Leite Integral
(81,'Leite Desnatado',4.29,'Leite desnatado - 1L',23 ,81 ,100), -- Leite Desnatado
(82,'Leite em Pó',15.00,'Leite em Pó Ninho -750gr' ,23 ,82 ,100); -- Leite em Pó

-- Inserindo Produtos para Grãos e Farinhas
INSERT INTO `produtos` (`IdProduto`, `Nome`, `Preco`, `Marca`, `IdCategoria`, `IdImagem`, `Estoque`) VALUES
(83,'Pó de Café',10.99,'Café torrado e moído -500g' ,24 ,83 ,100),   -- Pó de Café
(84,'Farinha de Trigo',5.50,'Farinha de trigo -1kg' ,24 ,84 ,100),   -- Farinha de Trigo
(85,'Farinha de Rosca',4.20,'Farinha de rosca -500g' ,24 ,85 ,100),   -- Farinha de Rosca
(86,'Farinha de Mandioca',6.90,'Farinha de mandioca -1kg' ,24 ,86 ,100),   -- Farinha de Mandioca
(87,'Farinha de Milho',3.50,'Farinha de milho -500g' ,24 ,87 ,100);   -- Farinha de Milho 

-- Inserindo Produtos para Pães
INSERT INTO `produtos` (`IdProduto`, `Nome`, `Preco`, `Marca`, `IdCategoria`, `IdImagem`, `Estoque`) VALUES
(88, 'Pão Francês', 12.99, 'Pão francês - 1kg', 25, 88, 100),  -- Pão Francês
(89, 'Pão Integral', 7.49, 'Pão integral - 500g', 25, 89, 100), -- Pão Integral
(90, 'Pão Doce', 9.99, 'Pão doce - 500g', 25, 90, 100), -- Pão Doce
(91, 'Pão de Leite', 7.49, 'Pão de Leite - 500g', 25, 91, 100); -- Pão de Leite

-- Inserindo Produtos para Doces
INSERT INTO `produtos` (`IdProduto`, `Nome`, `Preco`, `Marca`, `IdCategoria`, `IdImagem`, `Estoque`) VALUES
(92, 'Bolo de Chocolate', 15.99, 'Bolo caseiro de chocolate - 500g', 26, 92, 100), -- Bolo de Chocolate
(93, 'Bolo de Laranja', 13.99, 'Bolo caseiro de laranja - 500g', 26, 93, 100), -- Bolo de Laranja
(94, 'Bolo de Milho', 14.49, 'Bolo caseiro de milho - 500g', 26, 94, 100), -- Bolo de Milho
(95, 'Sonho', 3.50, 'Sonho recheado - unidade', 26, 95, 100), -- Sonho
(96,'Pudim',6.99,'Pudim de leite condensado -250g' ,26 ,96 ,100); -- Pudim

-- Inserindo Produtos para Frios
INSERT INTO `produtos` (`IdProduto`, `Nome`, `Preco`, `Marca`, `IdCategoria`, `IdImagem`, `Estoque`) VALUES
(97,'Queijo Mussarela',25.90,'Queijo mussarela fatiado -500g' ,27 ,97 ,100),   -- Queijo Mussarela
(98,'Presunto',19.90,'Presunto fatiado -500g' ,27 ,98 ,100),   -- Presunto 
(99,'Salame',15.50,'Salame italiano -300g' ,27 ,99 ,100),   -- Salame 
(100,'Queijo Provolone',8.90,'Queijo Provolona -200g' ,27 ,100 ,100),   -- Queijo Provolone 
(101,'Mortandela',12.99,'Mortandela -500g' ,27 ,101 ,100);   -- Mortandela 

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
    (1, '2024-01-01', '2025-12-31', 10.00),  -- Picanha com 10% de desconto
    (2, '2024-01-01', '2025-12-31', 5.00),   -- Alcatra com 5% de desconto
    (7, '2024-01-01', '2025-12-31', 8.00),   -- Salmão com 8% de desconto
    (9, '2024-01-01', '2025-12-31', 15.00),  -- Camarão com 15% de desconto
    (12, '2024-01-01', '2025-12-31', 7.00),  -- Coxa de frango com 7% de desconto
    (16, '2024-01-01', '2025-12-31', 12.00); -- Coração de Galinha com 12% de desconto

INSERT INTO `promocoes` (`IdProduto`, `DataInicio`, `DataFim`, `Desconto`) 
VALUES
    (17, '2024-12-01', '2024-12-31', 10.00),  -- Coca-Cola com 10% de desconto
    (18, '2024-12-01', '2024-12-31', 15.00),  -- Pepsi com 15% de desconto
    (19, '2024-12-01', '2024-12-31', 5.00),   -- Fanta com 5% de desconto
    (23, '2024-12-01', '2024-12-31', 7.00),   -- Suco Natural com 7% de desconto
    (24, '2024-12-01', '2024-12-31', 8.00),   -- Suco de Uva com 8% de desconto
    (28, '2024-12-01', '2024-12-31', 3.00);   -- Água com Gás com 3% de desconto

INSERT INTO `promocoes` (`IdProduto`, `DataInicio`, `DataFim`, `Desconto`) VALUES
(30, '2024-12-01', '2024-12-31', 10.00), -- Alface com 10% de desconto
(31, '2024-12-01', '2024-12-31', 15.00), -- Couve com 15% de desconto
(32, '2024-12-01', '2024-12-31', 5.00),  -- Espinafre com 5% de desconto
(39, '2024-12-01', '2024-12-31', 5.00),  -- Banana com 5% de desconto
(40, '2024-12-01', '2024-12-31', 7.00),  -- Kiwi com 7% de desconto
(41, '2024-12-01', '2024-12-31', 6.00),  -- Maçã com 6% de desconto
(50, '2024-12-01', '2024-12-31', 8.00);  -- Cenoura com 8% de desconto

INSERT INTO `promocoes` (`IdProduto`, `DataInicio`, `DataFim`, `Desconto`) VALUES 
(58, '2024-12-01', '2024-12-31', 10.00),   -- Sabonete com desconto de R$0.30 
(59, '2024-12-01', '2024-12-31', 15.00),   -- Shampoo com desconto de R$1.94 
(63, '2024-12-01', '2024-12-31', 5.00);    -- Desinfetante com desconto de R$0.50 

INSERT INTO `promocoes` (`IdProduto`, `DataInicio`, `DataFim`, `Desconto`) VALUES 
(72, '2024-12-01', '2024-12-31', 5.00),   -- Arroz com desconto de R$1.30 
(73, '2024-12-01', '2024-12-31', 1.00),   -- Feijão com desconto de R$0.50 
(78, '2024-12-01', '2024-12-31', 0.50);   -- Leite Condensado com desconto de R$0.50 

-- Inserindo Promoções
INSERT INTO `promocoes` (`IdProduto`, `DataInicio`, `DataFim`, `Desconto`) VALUES 
(88, '2024-12-01', '2024-12-31', 1.30),   -- Pão Francês com desconto de R$1.30 
(89, '2024-12-01', '2024-12-31', 0.50),   -- Pão Integral com desconto de R$0.50 
(92, '2024-12-01', '2024-12-31', 2.00);   -- Bolo de Chocolate com desconto de R$2.00 

-- --------------------------------------------------------

--
-- Estrutura para tabela `pedidos`
--

CREATE TABLE `pedidos` (
  `IdPedido` int(11) NOT NULL AUTO_INCREMENT,
  `DataPedido` DATETIME DEFAULT CURRENT_TIMESTAMP(),
  `DataRetirada` DATETIME DEFAULT NULL,
  DataExclusao DATETIME DEFAULT NULL,
  DataAgendada DATETIME DEFAULT NULL,
  EnderecoEntrega VARCHAR(255) DEFAULT NULL,
  MetodoPagamento ENUM('Cartao', 'Pix', 'Dinheiro') DEFAULT NULL,
  `ValorTotal` DECIMAL(10,2) NOT NULL,
  `Status` ENUM('Em Andamento', 'Finalizado', 'Cancelado') DEFAULT 'Em Andamento',
  `IdPessoa` int(11) NOT NULL,
  PRIMARY KEY (IdPedido),
  FOREIGN KEY (IdPessoa) REFERENCES pessoas(IdPessoa)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `pedidos`
--

INSERT INTO `pedidos` (`IdPedido`, `DataPedido`, `DataRetirada`, `ValorTotal`, `Status`, `IdPessoa`, `DataAgendada`) VALUES
(1, '2024-10-10', '2024-10-12', 45, 'Finalizado', 2, '2024-10-15'),
(2, '2024-10-15', '2024-10-16', 20, 'Finalizado', 3, '2024-10-15'),
(3, '2024-10-20', NULL, 33.5, 'Cancelado', 4, '2024-10-15'),
(4, '2024-10-25', NULL, 10, 'Em Andamento', 5, '2024-10-15');

-- --------------------------------------------------------

--
-- Estrutura para tabela `pedido_produtos`
--

CREATE TABLE `pedido_produtos` (
  `IdPedido` INT(11) NOT NULL,
  `IdProduto` INT(11) NOT NULL,
  `Quantidade` INT(11) NOT NULL,
  `PrecoUnitario` DECIMAL(10,2) NOT NULL,
  CONSTRAINT `fk_pedido` FOREIGN KEY (`IdPedido`) REFERENCES `pedidos` (`IdPedido`),
  CONSTRAINT `fk_produto` FOREIGN KEY (`IdProduto`) REFERENCES `produtos` (`IdProduto`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `pedido_produtos`
--

INSERT INTO `pedido_produtos` (`IdPedido`, `IdProduto`, `Quantidade`, `PrecoUnitario`) VALUES
(1, 1, 2, 3.50),
(1, 2, 3, 7.20),
(2, 3, 5, 5.80),
(3, 4, 1, 2.10),
(4, 5, 1, 9.40),
(1, 17, 2, 7.19),   -- Coca-Cola
(1, 18, 3, 5.94),   -- Pepsi
(2, 19, 5, 6.17),   -- Fanta
(3, 23, 1, 5.57),   -- Suco Natural
(4, 24, 1, 5.51),   -- Suco de Uva
(4, 28, 1, 2.90),   -- Água com Gás
(1, 30, 2, 2.69),   -- Alface
(1, 31, 3, 3.39),   -- Couve
(1, 39, 1, 6.29),   -- Banana
(2, 32, 5, 4.49),   -- Espinafre
(2, 40, 2, 6.49),   -- Kiwi
(3, 41, 1, 6.49),   -- Maçã
(3,50 ,1 ,4.59),    -- Cenoura
(1, 58, 2, 2.69),   -- Sabonete 
(1, 59, 3, 11.90),   -- Shampoo 
(2, 63, 5, 9.40),   -- Desinfetante 
(3 ,68 ,1 ,9.99),   -- Pá de Lixo 
(3 ,69 ,1 ,14.90),   -- Vassoura 
(1, 72, 2, 25.99),   -- Arroz 
(1, 73, 3, 7.99),    -- Feijão 
(2,78 ,5 ,5.49),     -- Leite Condensado 
(3 ,83 ,1 ,10.99),   -- Pó de Café 
(3 ,84 ,1 ,5.50),     -- Farinha de Trigo 
(1, 88, 2, 12.99),   -- Pão Francês 
(1, 89, 3, 7.49),    -- Pão Integral 
(2,92 ,5 ,15.49),    -- Bolo de Chocolate 
(3 ,97 ,1 ,25.90),   -- Queijo Mussarela 
(3 ,98 ,1 ,19.90);    -- Presunto 

-- -----------------------------------------------------------------------------


--
-- Estrutura para tabela `carrinho`
--

CREATE TABLE `carrinho` (
  IdCarrinho int NOT NULL AUTO_INCREMENT ,
  IdPessoa int NOT NULL,
  PRIMARY KEY (IdCarrinho),
  FOREIGN KEY (IdPessoa) REFERENCES pessoas(IdPessoa)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `carrinho_produtos`
--

CREATE TABLE `carrinho_produtos` (
  `Quantidade` int(11) NOT NULL,
  `IdCarrinho` int NOT NULL,
  `IdProduto` int NOT NULL,
  CONSTRAINT `fk_carrinho_produtos_produto` FOREIGN KEY (`IdProduto`) REFERENCES `produtos` (`IdProduto`),
  CONSTRAINT `fk_carrinho_produtos_carrinho` FOREIGN KEY (`IdCarrinho`) REFERENCES `carrinho` (`IdCarrinho`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;



-- ===========================================
-- SEÇÃO: TRIGGERS
-- ===========================================

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

-- Descrição: Este trigger é responsável por atualizar o status dos pedidos para 'Cancelado'
-- quando a DataExclusao de um cliente é definida, mas apenas se o status do pedido for 'Em Andamento'.
CREATE TRIGGER TRG_CANCELAR_PEDIDOS_ON_CLIENTE_EXCLUIDO
AFTER UPDATE ON pessoas
FOR EACH ROW
BEGIN
    -- Verifica se a DataExclusao do cliente foi alterada para não nula
    IF NEW.DataExclusao IS NOT NULL AND OLD.DataExclusao IS NULL THEN
        -- Atualiza o status dos pedidos associados ao cliente para 'Cancelado' se estiverem 'Em Andamento'
        UPDATE pedidos
        SET Status = 'Cancelado'
        WHERE IdPessoa = NEW.IdPessoa AND Status = 'Em Andamento';
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




-- ===========================================
-- SEÇÃO: FUNÇÕES
-- ===========================================

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




-- ===========================================
-- SEÇÃO: VIEWS
-- ===========================================

DELIMITER $$

-- Criação da view para promoções dos produtos
CREATE VIEW VW_PROMOCOES_PRODUTO_ATIVAS AS
SELECT 
    p.IdProduto, 
    p.Nome, 
    p.Marca,
    p.Estoque,
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

-- Criação da view para pedidos dos pessoas
CREATE VIEW VW_PEDIDOS_CLIENTE AS
SELECT 
    p.*, 
    c.Nome AS ClienteNome,
    c.Email AS ClienteEmail
FROM pedidos p
JOIN pessoas c ON p.IdPessoa = c.IdPessoa
WHERE p.DataExclusao IS NULL

$$
DELIMITER ;



DELIMITER $$

-- Criação de view para consultar todos os produtos cadastrados
CREATE VIEW VW_PRODUTOS AS
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

$$
DELIMITER ;


DELIMITER $$

-- Criação de view para consultar todos os produtos ativos
CREATE VIEW VW_PRODUTOS_ATIVOS AS
SELECT * FROM VW_PRODUTOS
WHERE dataexclusao IS NULL

$$
DELIMITER ;


DELIMITER $$

CREATE VIEW VW_USUARIOS_ATIVOS AS
/* 
    Esta VIEW combina informações da tabela de usuarios com suas respectivas entidades
    (funcionarios ou pessoas), retornando Nome, Email e Telefone conforme o tipo do usuário.
*/
SELECT 
    u.IdUsuario, /* Identificador único do usuário */
    u.Usuario, /* Nome de usuário para login */
    u.Senha, /* Senha criptografada */
    u.DataCriacao, /* Data de criação do registro */
    u.DataExclusao, /* Data de exclusão do usuário */
    p.IdPessoa,
    p.Nome, /* Nome do funcionário ou cliente */
    p.Email, /* Email do funcionário ou cliente */
    p.Telefone /* Telefone do funcionário ou cliente */
FROM 
    usuarios u
/* Associação com a tabela de pessoas */
JOIN pessoas p ON u.IdUsuario = p.IdUsuario AND p.DataExclusao IS NULL

WHERE u.dataexclusao IS NULL

$$
DELIMITER ;


/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
