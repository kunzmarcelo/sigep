-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: 28-Dez-2016 às 11:20
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
-- Estrutura da tabela `funcionario`
--

CREATE TABLE `funcionario` (
  `idfuncionario` int(11) NOT NULL,
  `nome` varchar(45) DEFAULT NULL,
  `sexo` varchar(45) DEFAULT NULL,
  `ativo` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `funcionario`
--

INSERT INTO `funcionario` (`idfuncionario`, `nome`, `sexo`, `ativo`) VALUES
(1, 'Aline Bode', 'Fem', 1),
(2, 'Daiana Barbosa Pereira', 'Fem', 1),
(3, 'Eliandra Crestine', 'Fem', 1),
(4, 'Elizandra Figueiredo', 'Fem', 1),
(5, 'Franciele Camargo Borba', 'Fem', 1),
(6, 'Janilce Sampaio			', 'Fem', 1),
(7, 'Leandro da Silva Lopes     ', 'Mas', 1),
(8, 'Luana Cristina Cavalheiro  ', 'Fem', 1),
(9, 'Luciane de Oliveira        ', 'Fem', 1),
(10, 'Marcelo Kunz               ', 'Mas', 1),
(11, 'Marta Cortes Do            ', 'Fem', 1),
(12, 'Selenir Correa Ramos       ', 'Fem', 1),
(13, 'Tania Geibmeier            ', 'Fem', 1),
(14, 'Tatiane Ingles da Silva    ', 'Fem', 1),
(15, 'Veronica Horst             ', 'Fem', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `funcionario`
--
ALTER TABLE `funcionario`
  ADD PRIMARY KEY (`idfuncionario`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `funcionario`
--
ALTER TABLE `funcionario`
  MODIFY `idfuncionario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
