-- phpMyAdmin SQL Dump
-- version 4.3.13
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: 09-Nov-2017 às 17:41
-- Versão do servidor: 5.6.23
-- PHP Version: 5.4.39

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `instrutor_db`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `tbl_fo`
--

CREATE TABLE IF NOT EXISTS `tbl_fo` (
  `fo_id` int(11) NOT NULL,
  `fo_data` varchar(255) NOT NULL,
  `fo_tipo` varchar(255) DEFAULT NULL,
  `fo_fato` longtext,
  `fo_obs` longtext,
  `fo_resp` varchar(255) DEFAULT NULL,
  `fo_cpf` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tbl_instrucao`
--

CREATE TABLE IF NOT EXISTS `tbl_instrucao` (
  `inst_id` int(11) NOT NULL,
  `inst_data` varchar(255) DEFAULT NULL,
  `inst_assunto` varchar(255) DEFAULT NULL,
  `inst_titulo` longtext,
  `inst_texto` longtext,
  `inst_situacao` varchar(255) DEFAULT NULL,
  `inst_resp` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tbl_militar`
--

CREATE TABLE IF NOT EXISTS `tbl_militar` (
  `mil_id` int(11) NOT NULL,
  `mil_nomecompleto` varchar(255) DEFAULT NULL,
  `mil_datanasc` varchar(255) DEFAULT NULL,
  `mil_pai` varchar(255) DEFAULT NULL,
  `mil_mae` varchar(255) DEFAULT NULL,
  `mil_ctt` varchar(255) DEFAULT NULL,
  `mil_naturalidade` varchar(255) DEFAULT NULL,
  `mil_end` varchar(255) DEFAULT NULL,
  `mil_pgrad` varchar(255) DEFAULT NULL,
  `mil_nomeguerra` varchar(255) DEFAULT NULL,
  `mil_idtmil` varchar(255) DEFAULT NULL,
  `mil_cpf` varchar(255) DEFAULT NULL,
  `mil_dataincorp` varchar(255) DEFAULT NULL,
  `mil_pel` varchar(255) DEFAULT NULL,
  `mil_funcao` varchar(255) DEFAULT NULL,
  `mil_foto` varchar(255) DEFAULT NULL,
  `mil_antiguidade` varchar(255) DEFAULT NULL,
  `mil_situacao` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tbl_qualificar`
--

CREATE TABLE IF NOT EXISTS `tbl_qualificar` (
  `qual_id` int(11) NOT NULL,
  `qual_cpf` varchar(255) DEFAULT NULL,
  `qual_qualific` longtext,
  `qual_resp` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tbl_usuarios`
--

CREATE TABLE IF NOT EXISTS `tbl_usuarios` (
  `usu_id` int(11) NOT NULL,
  `usu_nome` varchar(255) DEFAULT NULL,
  `usu_senha` varchar(255) DEFAULT NULL,
  `usu_tipo` varchar(255) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `tbl_usuarios`
--

INSERT INTO `tbl_usuarios` (`usu_id`, `usu_nome`, `usu_senha`, `usu_tipo`) VALUES
(1, 'miyashiro', '123456', '1'),
(2, 'convidado', 'convidado', '3'),
(3, 'usuario', 'usuario', '2');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_fo`
--
ALTER TABLE `tbl_fo`
  ADD PRIMARY KEY (`fo_id`);

--
-- Indexes for table `tbl_instrucao`
--
ALTER TABLE `tbl_instrucao`
  ADD PRIMARY KEY (`inst_id`);

--
-- Indexes for table `tbl_militar`
--
ALTER TABLE `tbl_militar`
  ADD PRIMARY KEY (`mil_id`);

--
-- Indexes for table `tbl_qualificar`
--
ALTER TABLE `tbl_qualificar`
  ADD PRIMARY KEY (`qual_id`);

--
-- Indexes for table `tbl_usuarios`
--
ALTER TABLE `tbl_usuarios`
  ADD PRIMARY KEY (`usu_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_fo`
--
ALTER TABLE `tbl_fo`
  MODIFY `fo_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `tbl_instrucao`
--
ALTER TABLE `tbl_instrucao`
  MODIFY `inst_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `tbl_militar`
--
ALTER TABLE `tbl_militar`
  MODIFY `mil_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `tbl_qualificar`
--
ALTER TABLE `tbl_qualificar`
  MODIFY `qual_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `tbl_usuarios`
--
ALTER TABLE `tbl_usuarios`
  MODIFY `usu_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
