-- phpMyAdmin SQL Dump
-- version 4.0.4.2
-- http://www.phpmyadmin.net
--
-- Máquina: localhost
-- Data de Criação: 10-Nov-2026 às 02:30
-- Versão do servidor: 5.6.13
-- versão do PHP: 5.4.17

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de Dados: `sistema_produtos`
--
CREATE DATABASE IF NOT EXISTS `sistema_produtos` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `sistema_produtos`;

-- --------------------------------------------------------

--
-- Estrutura da tabela `produtos`
--

CREATE TABLE IF NOT EXISTS `produtos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `descricao` varchar(150) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `categoria` varchar(100) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `valor_compra` decimal(10,2) NOT NULL,
  `valor_venda` decimal(10,2) NOT NULL,
  `qtd_estoque` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

--
-- Extraindo dados da tabela `produtos`
--

INSERT INTO `produtos` (`id`, `descricao`, `categoria`, `valor_compra`, `valor_venda`, `qtd_estoque`) VALUES
(5, 'Notebook Samsung', 'Informática', '3800.00', '4120.00', 3),
(6, 'iPhone 18 Pro Max 1TB', 'Eletrônicos', '13000.00', '14000.00', 2),
(7, 'Fone de Ouvido', 'Outros', '25.00', '32.00', 3),
(8, 'Cartão de Memória 128G', 'Outros', '85.00', '100.00', 2);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
