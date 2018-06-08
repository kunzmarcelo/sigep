-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Tempo de geração: 20/07/2017 às 14:26
-- Versão do servidor: 10.1.13-MariaDB
-- Versão do PHP: 5.6.23

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `diario_bordo`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `numero_pecas`
--

CREATE TABLE `numero_pecas` (
  `id` int(11) NOT NULL,
  `data_ini` date DEFAULT NULL,
  `data_fim` date DEFAULT NULL,
  `n_peca` int(11) DEFAULT NULL,
  `salario_producao` decimal(10,2) DEFAULT NULL,
  `id_funcionario` int(11) NOT NULL,
  `id_salario` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Fazendo dump de dados para tabela `numero_pecas`
--

INSERT INTO `numero_pecas` (`id`, `data_ini`, `data_fim`, `n_peca`, `salario_producao`, `id_funcionario`, `id_salario`) VALUES
(7, '2017-02-01', '2017-02-28', 2155, '1052.63', 1, 2),
(8, '2017-02-01', '2017-02-28', 5016, '1001.78', 2, 2),
(9, '2017-02-01', '2017-02-28', 1905, '882.68', 4, 2),
(10, '2017-02-01', '2017-02-28', 2439, '1023.68', 5, 2),
(11, '2017-02-01', '2017-02-28', 1727, '1048.13', 6, 2),
(12, '2017-02-01', '2017-02-28', 1202, '1081.80', 7, 2),
(13, '2017-02-01', '2017-02-28', 121, '1197.90', 11, 2),
(14, '2017-02-01', '2017-02-28', 3783, '1066.88', 13, 2),
(15, '2017-02-01', '2017-02-28', 2398, '1107.15', 16, 2),
(16, '2017-03-01', '2017-03-31', 2264, '991.54', 1, 2),
(17, '2017-03-01', '2017-03-31', 2678, '1005.08', 2, 2),
(18, '2017-03-01', '2017-03-31', 2828, '1062.90', 4, 2),
(19, '2017-03-01', '2017-03-31', 1274, '1121.03', 5, 2),
(20, '2017-03-01', '2017-03-31', 270, '1000.02', 11, 2),
(21, '2017-03-01', '2017-03-31', 7164, '1022.63', 13, 2),
(22, '2017-03-01', '2017-03-31', 3580, '1066.14', 16, 2),
(23, '2017-04-01', '2017-04-30', 1316, '972.54', 1, 2),
(24, '2017-04-01', '2017-04-30', 2221, '1018.08', 2, 2),
(25, '2017-04-01', '2017-04-30', 1986, '774.00', 4, 2),
(26, '2017-04-01', '2017-04-30', 1282, '916.16', 5, 2),
(27, '2017-04-01', '2017-04-30', 1113, '887.76', 6, 2),
(28, '2017-04-01', '2017-04-30', 1672, '969.76', 7, 2),
(29, '2017-04-01', '2017-04-30', 734, '1000.51', 11, 2),
(30, '2017-04-01', '2017-04-30', 4579, '916.94', 13, 2),
(31, '2017-04-01', '2017-04-30', 2587, '845.90', 16, 2),
(32, '2017-03-01', '2017-03-31', 1725, '1000.50', 7, 2),
(33, '2017-03-01', '2017-03-31', 1509, '1165.62', 6, 2);

--
-- Índices de tabelas apagadas
--

--
-- Índices de tabela `numero_pecas`
--
ALTER TABLE `numero_pecas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_numero_pecas_funcinario1_idx` (`id_funcionario`),
  ADD KEY `fk_numero_pecas_salario1_idx` (`id_salario`);

--
-- AUTO_INCREMENT de tabelas apagadas
--

--
-- AUTO_INCREMENT de tabela `numero_pecas`
--
ALTER TABLE `numero_pecas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;
--
-- Restrições para dumps de tabelas
--

--
-- Restrições para tabelas `numero_pecas`
--
ALTER TABLE `numero_pecas`
  ADD CONSTRAINT `fk_numero_pecas_funcinario1` FOREIGN KEY (`id_funcionario`) REFERENCES `funcionario` (`id_funcionario`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_numero_pecas_salario1` FOREIGN KEY (`id_salario`) REFERENCES `salario` (`id_salario`) ON DELETE NO ACTION ON UPDATE NO ACTION;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
