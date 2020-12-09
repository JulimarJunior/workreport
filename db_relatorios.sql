-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: 09-Dez-2020 às 18:38
-- Versão do servidor: 5.7.19
-- PHP Version: 5.6.31

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_relatorios`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_cargo`
--

DROP TABLE IF EXISTS `tb_cargo`;
CREATE TABLE IF NOT EXISTS `tb_cargo` (
  `cd_cargo` int(11) NOT NULL,
  `nm_cargo` char(20) DEFAULT NULL,
  PRIMARY KEY (`cd_cargo`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `tb_cargo`
--

INSERT INTO `tb_cargo` (`cd_cargo`, `nm_cargo`) VALUES
(1, 'Programador');

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_relatorio`
--

DROP TABLE IF EXISTS `tb_relatorio`;
CREATE TABLE IF NOT EXISTS `tb_relatorio` (
  `cd_relatorio` int(11) NOT NULL AUTO_INCREMENT,
  `ds_relatorio` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `dt_relatorio` date NOT NULL,
  `dt_envio` datetime NOT NULL,
  `cd_usuario` int(11) NOT NULL,
  PRIMARY KEY (`cd_relatorio`),
  KEY `fk_relatorio_usuario` (`cd_usuario`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `tb_relatorio`
--

INSERT INTO `tb_relatorio` (`cd_relatorio`, `ds_relatorio`, `dt_relatorio`, `dt_envio`, `cd_usuario`) VALUES
(3, '\n						09:00  -  10:00&nbsp;<b>Comi toissinho</b>\n				<br>\n				<b>Descrição:</b>', '2020-12-08', '2020-12-09 18:37:00', 1);

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_usuario`
--

DROP TABLE IF EXISTS `tb_usuario`;
CREATE TABLE IF NOT EXISTS `tb_usuario` (
  `cd_usuario` int(11) NOT NULL AUTO_INCREMENT,
  `nm_usuario` char(255) NOT NULL,
  `cd_cargo` int(11) NOT NULL,
  `ds_email` char(255) NOT NULL,
  `ds_senha` char(40) NOT NULL,
  PRIMARY KEY (`cd_usuario`),
  KEY `fk_usuario_cargo` (`cd_cargo`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `tb_usuario`
--

INSERT INTO `tb_usuario` (`cd_usuario`, `nm_usuario`, `cd_cargo`, `ds_email`, `ds_senha`) VALUES
(1, 'Julimar Gomes', 1, 'julimar.junior@summercomunicacao.com.br', '1234567');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
