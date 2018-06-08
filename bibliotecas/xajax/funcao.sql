-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: 28-Dez-2016 às 11:51
-- Versão do servidor: 10.1.13-MariaDB
-- PHP Version: 5.6.23

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `producao_funcionario`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `funcao`
--

CREATE TABLE `funcao` (
  `idfuncao` int(11) NOT NULL,
  `funcao` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `funcao`
--

INSERT INTO `funcao` (`idfuncao`, `funcao`) VALUES
(1, 'Barra'),
(2, 'Botão'),
(3, 'Casedora'),
(4, 'Corte'),
(5, 'Despache (mercadoria'),
(6, 'Dobra'),
(7, 'Embalagem'),
(8, 'Etiqueta (gola)'),
(9, 'Fio'),
(10, 'Frente'),
(11, 'Gola (montagem na peca)'),
(12, 'Gola (montagem'),
(13, 'Malharia'),
(14, 'Manga'),
(15, 'Montagem (peça)'),
(16, 'Passada final'),
(17, 'Passada inicial'),
(18, 'Pencil'),
(19, 'Prega'),
(20, 'Punho (montagem final)'),
(21, 'Punho (montagem na peça)'),
(22, 'Punho Preparação'),
(23, 'Separação (final'),
(24, 'Separacao (inicial)'),
(25, 'Travete');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `funcao`
--
ALTER TABLE `funcao`
  ADD PRIMARY KEY (`idfuncao`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `funcao`
--
ALTER TABLE `funcao`
  MODIFY `idfuncao` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
