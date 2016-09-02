-- phpMyAdmin SQL Dump
-- version 4.0.4.2
-- http://www.phpmyadmin.net
--
-- Máquina: localhost
-- Data de Criação: 01-Set-2016 às 11:36
-- Versão do servidor: 5.6.13
-- versão do PHP: 5.4.17

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de Dados: `ponto`
--
CREATE DATABASE IF NOT EXISTS `ponto` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `ponto`;

-- --------------------------------------------------------

--
-- Estrutura da tabela `abono`
--

CREATE TABLE IF NOT EXISTS `abono` (
  `nr_abono` int(12) NOT NULL AUTO_INCREMENT,
  `id_pessoa` int(6) NOT NULL,
  `matricula` int(8) DEFAULT NULL,
  `nr_vinculo` int(1) DEFAULT NULL,
  `data_abono` datetime DEFAULT NULL,
  `periodo_abono` int(4) DEFAULT NULL,
  `justificativa` text,
  `id_pessoa_certificacao` int(6) DEFAULT NULL,
  `data_hora_certificacao` datetime DEFAULT NULL,
  `indicador_certificado` char(1) DEFAULT NULL,
  `id_pessoa_registro` int(6) NOT NULL,
  `data_hora_registro` datetime NOT NULL,
  `ip_registro` varchar(39) NOT NULL,
  `justificativa_certificacao` varchar(512) DEFAULT NULL,
  `nr_justificativa` int(2) DEFAULT NULL,
  `indicador_excluido` char(1) DEFAULT NULL,
  PRIMARY KEY (`nr_abono`),
  KEY `id_pessoa` (`id_pessoa`),
  KEY `id_pessoa_certificacao` (`id_pessoa_certificacao`),
  KEY `id_pessoa_registro` (`id_pessoa_registro`),
  KEY `nr_justificativa` (`nr_justificativa`),
  KEY `matricula` (`matricula`,`nr_vinculo`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Extraindo dados da tabela `abono`
--

INSERT INTO `abono` (`nr_abono`, `id_pessoa`, `matricula`, `nr_vinculo`, `data_abono`, `periodo_abono`, `justificativa`, `id_pessoa_certificacao`, `data_hora_certificacao`, `indicador_certificado`, `id_pessoa_registro`, `data_hora_registro`, `ip_registro`, `justificativa_certificacao`, `nr_justificativa`, `indicador_excluido`) VALUES
(1, 4, 121212, 1, '2016-08-25 00:00:00', 480, 'Teste', 3, '2016-08-29 17:54:57', 'S', 4, '2016-08-29 17:54:09', '::1', '', NULL, NULL);

-- --------------------------------------------------------

--
-- Estrutura da tabela `ajuste`
--

CREATE TABLE IF NOT EXISTS `ajuste` (
  `nr_ajuste` int(12) NOT NULL AUTO_INCREMENT,
  `id_pessoa` int(6) NOT NULL,
  `matricula` int(8) DEFAULT NULL,
  `nr_vinculo` int(1) DEFAULT NULL,
  `data_hora_ponto` datetime NOT NULL,
  `entrada_saida` char(1) NOT NULL,
  `id_pessoa_registro` int(6) NOT NULL,
  `data_hora_registro` datetime NOT NULL,
  `ip_registro` varchar(39) NOT NULL,
  `justificativa` text,
  `id_pessoa_certificacao` int(6) DEFAULT NULL,
  `data_hora_certificacao` datetime DEFAULT NULL,
  `indicador_certificado` char(1) DEFAULT NULL,
  `nr_ponto` int(12) DEFAULT NULL,
  `nr_justificativa` int(2) DEFAULT NULL,
  `justificativa_certificacao` varchar(512) DEFAULT NULL,
  `indicador_excluido` char(1) DEFAULT NULL,
  PRIMARY KEY (`nr_ajuste`),
  KEY `id_pessoa` (`id_pessoa`),
  KEY `matricula` (`matricula`,`nr_vinculo`),
  KEY `id_pessoa_registro` (`id_pessoa_registro`),
  KEY `id_pessoa_certificacao` (`id_pessoa_certificacao`),
  KEY `nr_ponto` (`nr_ponto`),
  KEY `nr_justificativa` (`nr_justificativa`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9 ;

--
-- Extraindo dados da tabela `ajuste`
--

INSERT INTO `ajuste` (`nr_ajuste`, `id_pessoa`, `matricula`, `nr_vinculo`, `data_hora_ponto`, `entrada_saida`, `id_pessoa_registro`, `data_hora_registro`, `ip_registro`, `justificativa`, `id_pessoa_certificacao`, `data_hora_certificacao`, `indicador_certificado`, `nr_ponto`, `nr_justificativa`, `justificativa_certificacao`, `indicador_excluido`) VALUES
(3, 1, 1234567, 1, '2016-08-16 08:26:00', 'E', 1, '2016-08-19 19:25:22', '::1', 'aebebbngfn', 2, '2016-08-26 19:48:59', 'S', 3, NULL, '', NULL),
(4, 1, 1234567, 1, '2016-08-16 14:30:00', 'S', 1, '2016-08-19 19:28:45', '::1', 'N', 2, '2016-08-26 19:55:51', 'S', NULL, NULL, NULL, NULL),
(5, 1, 1234567, 1, '2016-08-19 18:45:00', 'S', 1, '2016-08-19 19:29:17', '::1', 'fdsbhfdhf', 2, '2016-08-26 19:56:27', 'S', 4, NULL, NULL, NULL),
(6, 1, 1234567, 1, '2016-08-19 08:20:00', 'E', 1, '2016-08-19 19:29:38', '::1', 'dasfsfsd', 2, '2016-08-26 19:56:34', 'S', NULL, NULL, NULL, NULL),
(7, 1, 1234567, 1, '2016-08-19 13:00:00', 'S', 1, '2016-08-19 19:29:38', '::1', 'dasfsfsd', 2, '2016-08-26 19:56:34', 'S', NULL, NULL, NULL, NULL),
(8, 1, 1234567, 1, '2016-08-19 14:00:00', 'E', 1, '2016-08-19 19:29:53', '::1', 'dfswgfdsgf', 2, '2016-08-26 19:56:34', 'S', NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Estrutura da tabela `arquivo_ajuste`
--

CREATE TABLE IF NOT EXISTS `arquivo_ajuste` (
  `nr_arquivo_ajuste` int(12) NOT NULL AUTO_INCREMENT,
  `nr_ajuste` int(12) DEFAULT NULL,
  `nr_abono` int(12) DEFAULT NULL,
  `cod_repositorio` varchar(12) NOT NULL,
  `descricao_arquivo` varchar(256) DEFAULT NULL,
  PRIMARY KEY (`nr_arquivo_ajuste`),
  KEY `nr_ajuste` (`nr_ajuste`),
  KEY `nr_abono` (`nr_abono`),
  KEY `cod_repositorio` (`cod_repositorio`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `calendario_feriados`
--

CREATE TABLE IF NOT EXISTS `calendario_feriados` (
  `dia` int(2) NOT NULL,
  `mes` int(2) NOT NULL,
  `ano` int(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura da tabela `categoria`
--

CREATE TABLE IF NOT EXISTS `categoria` (
  `id_categoria` int(3) NOT NULL AUTO_INCREMENT,
  `nome_categoria` varchar(255) NOT NULL,
  `regime_trabalho` char(2) NOT NULL,
  PRIMARY KEY (`id_categoria`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Extraindo dados da tabela `categoria`
--

INSERT INTO `categoria` (`id_categoria`, `nome_categoria`, `regime_trabalho`) VALUES
(1, 'Assistente administrativo', '40');

-- --------------------------------------------------------

--
-- Estrutura da tabela `ch_mes_servidor`
--

CREATE TABLE IF NOT EXISTS `ch_mes_servidor` (
  `nr_cargahoraria` int(12) NOT NULL AUTO_INCREMENT,
  `id_pessoa` int(6) NOT NULL,
  `matricula` int(8) NOT NULL,
  `nr_vinculo` int(1) NOT NULL,
  `ano` int(4) NOT NULL,
  `mes` int(2) NOT NULL,
  `data_inicio_mes` datetime NOT NULL,
  `nr_minutos_trabalho` int(5) NOT NULL,
  `nr_minutos_abono` int(5) NOT NULL,
  `nr_minutos_afastamento` int(5) NOT NULL,
  `nr_minutos_previsto` int(5) NOT NULL,
  `nr_minutos_saldo` int(6) DEFAULT NULL,
  `id_pessoa_atualizacao` int(6) NOT NULL,
  `data_atualizacao` datetime NOT NULL,
  `ip_atualizacao` varchar(39) NOT NULL,
  `nr_minutos_compensacao` int(5) DEFAULT NULL,
  PRIMARY KEY (`nr_cargahoraria`),
  KEY `id_pessoa` (`id_pessoa`),
  KEY `matricula` (`matricula`,`nr_vinculo`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=13 ;

--
-- Extraindo dados da tabela `ch_mes_servidor`
--

INSERT INTO `ch_mes_servidor` (`nr_cargahoraria`, `id_pessoa`, `matricula`, `nr_vinculo`, `ano`, `mes`, `data_inicio_mes`, `nr_minutos_trabalho`, `nr_minutos_abono`, `nr_minutos_afastamento`, `nr_minutos_previsto`, `nr_minutos_saldo`, `id_pessoa_atualizacao`, `data_atualizacao`, `ip_atualizacao`, `nr_minutos_compensacao`) VALUES
(1, 1, 1234567, 1, 2016, 4, '2016-04-01 00:00:00', 9148, 0, 0, 10080, -932, 3, '2016-08-29 18:41:41', '::1', 0),
(2, 2, 123123, 1, 2016, 4, '2016-04-01 00:00:00', 8473, 0, 0, 10080, -1607, 3, '2016-08-29 18:41:41', '::1', 0),
(3, 3, 11223344, 1, 2016, 4, '2016-04-01 00:00:00', 8849, 0, 0, 10080, -1231, 3, '2016-08-29 18:41:41', '::1', 0),
(4, 4, 121212, 1, 2016, 4, '2016-04-01 00:00:00', 6669, 0, 0, 10080, -3411, 3, '2016-08-29 18:41:41', '::1', 0),
(5, 1, 1234567, 1, 2016, 7, '2016-07-01 00:00:00', 2421, 0, 0, 10080, -7659, 3, '2016-08-29 18:41:52', '::1', 0),
(6, 1, 1234567, 1, 2016, 5, '2016-05-01 00:00:00', 5824, 0, 0, 10560, -4736, 3, '2016-08-29 18:41:45', '::1', 0),
(7, 2, 123123, 1, 2016, 5, '2016-05-01 00:00:00', 9143, 0, 0, 10560, -1417, 3, '2016-08-29 18:41:45', '::1', 0),
(8, 3, 11223344, 1, 2016, 5, '2016-05-01 00:00:00', 9505, 0, 0, 10560, -1055, 3, '2016-08-29 18:41:46', '::1', 0),
(9, 4, 121212, 1, 2016, 5, '2016-05-01 00:00:00', 6141, 0, 0, 10560, -4419, 3, '2016-08-29 18:41:46', '::1', 0),
(10, 1, 1234567, 1, 2016, 6, '2016-06-01 00:00:00', 969, 0, 0, 10560, -9591, 3, '2016-08-29 18:41:49', '::1', 0),
(11, 2, 123123, 1, 2016, 6, '2016-06-01 00:00:00', 2408, 0, 0, 10560, -8152, 3, '2016-08-29 18:41:49', '::1', 0),
(12, 4, 121212, 1, 2016, 6, '2016-06-01 00:00:00', 1285, 0, 0, 10560, -9275, 3, '2016-08-29 18:41:50', '::1', 0);

-- --------------------------------------------------------

--
-- Estrutura da tabela `compensacao`
--

CREATE TABLE IF NOT EXISTS `compensacao` (
  `nr_compensacao` int(12) NOT NULL AUTO_INCREMENT,
  `id_pessoa` int(6) NOT NULL,
  `matricula` int(8) NOT NULL,
  `nr_vinculo` int(1) NOT NULL,
  `periodo_compensacao` int(5) NOT NULL,
  `data_compensacao` datetime DEFAULT NULL,
  `descricao_compensacao` varchar(512) DEFAULT NULL,
  `justificativa` varchar(512) DEFAULT NULL,
  `id_pessoa_registro` int(6) NOT NULL,
  `data_hora_registro` datetime NOT NULL,
  `ip_registro` varchar(39) NOT NULL,
  `id_pessoa_certificacao` int(6) DEFAULT NULL,
  `data_hora_certificacao` datetime DEFAULT NULL,
  `indicador_certificado` char(1) DEFAULT NULL,
  `justificativa_certificacao` varchar(512) DEFAULT NULL,
  `indicador_excluido` char(1) DEFAULT NULL,
  PRIMARY KEY (`nr_compensacao`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `dado_funcional`
--

CREATE TABLE IF NOT EXISTS `dado_funcional` (
  `matricula` int(8) NOT NULL,
  `nr_vinculo` int(1) NOT NULL,
  `id_pessoa` int(6) NOT NULL,
  `regime_trabalho` char(2) NOT NULL,
  `id_grupo` int(2) NOT NULL,
  `id_categoria` int(3) NOT NULL,
  `orgao_lotacao` int(5) NOT NULL,
  `orgao_exercicio` int(5) NOT NULL,
  `data_ingresso` datetime NOT NULL,
  `data_desligamento` datetime DEFAULT NULL,
  `data_aposentadoria` datetime DEFAULT NULL,
  PRIMARY KEY (`matricula`,`nr_vinculo`),
  KEY `id_pessoa` (`id_pessoa`),
  KEY `id_grupo` (`id_grupo`),
  KEY `id_categoria` (`id_categoria`),
  KEY `orgao_lotacao` (`orgao_lotacao`),
  KEY `orgao_exercicio` (`orgao_exercicio`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `dado_funcional`
--

INSERT INTO `dado_funcional` (`matricula`, `nr_vinculo`, `id_pessoa`, `regime_trabalho`, `id_grupo`, `id_categoria`, `orgao_lotacao`, `orgao_exercicio`, `data_ingresso`, `data_desligamento`, `data_aposentadoria`) VALUES
(121212, 1, 4, '40', 1, 1, 2, 3, '2000-02-01 00:00:00', NULL, NULL),
(123123, 1, 2, '40', 1, 1, 2, 2, '2000-01-06 00:00:00', NULL, NULL),
(1234567, 1, 1, '40', 1, 1, 2, 3, '2000-01-02 00:00:00', NULL, NULL),
(11223344, 1, 3, '40', 2, 1, 1, 1, '2000-01-18 00:00:00', NULL, NULL);

-- --------------------------------------------------------

--
-- Estrutura da tabela `definicoes_orgao`
--

CREATE TABLE IF NOT EXISTS `definicoes_orgao` (
  `id_orgao` int(5) NOT NULL,
  `hora_inicio_expediente` datetime DEFAULT NULL,
  `hora_fim_expediente` datetime DEFAULT NULL,
  `permite_ocorrencia` char(1) DEFAULT NULL,
  `id_pessoa_atualizacao` int(6) NOT NULL,
  `data_atualizacao` datetime NOT NULL,
  `hora_inicio_expediente_sabado` datetime DEFAULT NULL,
  `hora_fim_expediente_sabado` datetime DEFAULT NULL,
  `hora_inicio_expediente_domingo` datetime DEFAULT NULL,
  `hora_fim_expediente_domingo` datetime DEFAULT NULL,
  PRIMARY KEY (`id_orgao`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `definicoes_orgao`
--

INSERT INTO `definicoes_orgao` (`id_orgao`, `hora_inicio_expediente`, `hora_fim_expediente`, `permite_ocorrencia`, `id_pessoa_atualizacao`, `data_atualizacao`, `hora_inicio_expediente_sabado`, `hora_fim_expediente_sabado`, `hora_inicio_expediente_domingo`, `hora_fim_expediente_domingo`) VALUES
(3, '1970-01-01 07:00:00', '1970-01-01 22:00:00', NULL, 1, '2016-08-29 17:12:33', NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Estrutura da tabela `frequencia`
--

CREATE TABLE IF NOT EXISTS `frequencia` (
  `nr_frequencia` int(8) NOT NULL AUTO_INCREMENT,
  `matricula` int(8) NOT NULL,
  `nr_vinculo` int(1) NOT NULL,
  `nr_dias` int(11) NOT NULL,
  `data_frequencia` datetime NOT NULL,
  `data_fim_frequencia` datetime DEFAULT NULL,
  `cod_frequencia` int(3) NOT NULL,
  PRIMARY KEY (`nr_frequencia`),
  KEY `matricula` (`matricula`,`nr_vinculo`),
  KEY `tipo_frequencia` (`cod_frequencia`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Extraindo dados da tabela `frequencia`
--

INSERT INTO `frequencia` (`nr_frequencia`, `matricula`, `nr_vinculo`, `nr_dias`, `data_frequencia`, `data_fim_frequencia`, `cod_frequencia`) VALUES
(1, 1234567, 1, 3, '2016-08-29 00:00:00', '2016-08-31 00:00:00', 1);

-- --------------------------------------------------------

--
-- Estrutura da tabela `grupo_emprego`
--

CREATE TABLE IF NOT EXISTS `grupo_emprego` (
  `id_grupo` int(2) NOT NULL,
  `segmento_grupo` char(1) NOT NULL,
  `nome_grupo` varchar(150) NOT NULL,
  PRIMARY KEY (`id_grupo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `grupo_emprego`
--

INSERT INTO `grupo_emprego` (`id_grupo`, `segmento_grupo`, `nome_grupo`) VALUES
(1, 'T', 'Técnico-administrativo'),
(2, 'D', 'Docente');

-- --------------------------------------------------------

--
-- Estrutura da tabela `justificativa_ajuste`
--

CREATE TABLE IF NOT EXISTS `justificativa_ajuste` (
  `nr_justificativa` int(2) NOT NULL AUTO_INCREMENT,
  `texto_justificativa` varchar(255) NOT NULL,
  `tipo_justificativa` char(1) DEFAULT NULL,
  PRIMARY KEY (`nr_justificativa`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `log_erro_acesso_registro`
--

CREATE TABLE IF NOT EXISTS `log_erro_acesso_registro` (
  `nr_log` int(12) NOT NULL AUTO_INCREMENT,
  `id_pessoa` int(6) NOT NULL,
  `matricula` int(8) NOT NULL,
  `nr_vinculo` int(1) NOT NULL,
  `data_log` datetime NOT NULL,
  `mensagem_log` varchar(512) NOT NULL,
  `ip_log` varchar(39) NOT NULL,
  `host_log` varchar(100) NOT NULL,
  PRIMARY KEY (`nr_log`),
  KEY `id_pessoa` (`id_pessoa`),
  KEY `matricula` (`matricula`,`nr_vinculo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `orgao`
--

CREATE TABLE IF NOT EXISTS `orgao` (
  `id_orgao` int(5) NOT NULL AUTO_INCREMENT,
  `sigla_orgao` varchar(10) NOT NULL,
  `nome_orgao` varchar(255) NOT NULL,
  `email` varchar(150) DEFAULT NULL,
  `matricula_dirigente` int(8) DEFAULT NULL,
  `matricula_substituto` int(8) DEFAULT NULL,
  `id_orgao_superior` int(5) DEFAULT NULL,
  PRIMARY KEY (`id_orgao`),
  KEY `matricula_dirigente` (`matricula_dirigente`),
  KEY `matricula_substituto` (`matricula_substituto`),
  KEY `id_orgao_superior` (`id_orgao_superior`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Extraindo dados da tabela `orgao`
--

INSERT INTO `orgao` (`id_orgao`, `sigla_orgao`, `nome_orgao`, `email`, `matricula_dirigente`, `matricula_substituto`, `id_orgao_superior`) VALUES
(1, 'UNI', 'Universidade', NULL, 11223344, NULL, NULL),
(2, 'UAC', 'Unidade Acadêmica', NULL, 123123, NULL, 1),
(3, 'DEPA', 'Departamento de Afazeres', NULL, 1234567, NULL, 2);

-- --------------------------------------------------------

--
-- Estrutura da tabela `permissao`
--

CREATE TABLE IF NOT EXISTS `permissao` (
  `id_aplicacao` int(6) NOT NULL,
  `id_pessoa` int(6) NOT NULL,
  `id_orgao` int(5) NOT NULL,
  `data_expiracao` datetime DEFAULT NULL,
  PRIMARY KEY (`id_aplicacao`,`id_pessoa`),
  KEY `id_pessoa` (`id_pessoa`),
  KEY `id_orgao` (`id_orgao`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `permissao`
--

INSERT INTO `permissao` (`id_aplicacao`, `id_pessoa`, `id_orgao`, `data_expiracao`) VALUES
(1, 1, 2, NULL);

-- --------------------------------------------------------

--
-- Estrutura da tabela `pessoa`
--

CREATE TABLE IF NOT EXISTS `pessoa` (
  `id_pessoa` int(6) NOT NULL,
  `nome_pessoa` varchar(150) NOT NULL,
  `email` varchar(150) NOT NULL,
  `tipo_foto` char(4) DEFAULT NULL,
  PRIMARY KEY (`id_pessoa`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `pessoa`
--

INSERT INTO `pessoa` (`id_pessoa`, `nome_pessoa`, `email`, `tipo_foto`) VALUES
(1, 'Mário Quintana', 'marioquintana@email.com', 'jpg'),
(2, 'Érico Veríssimo', 'erico@mail.com', 'jpg'),
(3, 'Elis Regina', 'elis@mail.com', 'jpg'),
(4, 'Lya Luft', 'lya@mail.com', 'jpg');

-- --------------------------------------------------------

--
-- Estrutura da tabela `ponto`
--

CREATE TABLE IF NOT EXISTS `ponto` (
  `nr_ponto` int(12) NOT NULL AUTO_INCREMENT,
  `id_pessoa` int(6) NOT NULL,
  `matricula` int(8) DEFAULT NULL,
  `nr_vinculo` int(1) DEFAULT NULL,
  `data_hora_ponto` datetime NOT NULL,
  `entrada_saida` char(1) NOT NULL,
  `id_pessoa_registro` int(6) NOT NULL,
  `data_hora_registro` datetime DEFAULT NULL,
  `ip_registro` varchar(39) NOT NULL,
  `ambiente_registro` text,
  PRIMARY KEY (`nr_ponto`),
  KEY `id_pessoa` (`id_pessoa`),
  KEY `matricula` (`matricula`,`nr_vinculo`),
  KEY `id_pessoa_registro` (`id_pessoa_registro`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=604 ;

--
-- Extraindo dados da tabela `ponto`
--

INSERT INTO `ponto` (`nr_ponto`, `id_pessoa`, `matricula`, `nr_vinculo`, `data_hora_ponto`, `entrada_saida`, `id_pessoa_registro`, `data_hora_registro`, `ip_registro`, `ambiente_registro`) VALUES
(1, 1, 1234567, 1, '2016-08-16 16:55:49', 'E', 1, '2016-08-16 16:56:19', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.36'),
(2, 1, 1234567, 1, '2016-08-16 17:26:10', 'S', 1, '2016-08-16 17:26:40', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.36'),
(3, 1, 1234567, 1, '2016-08-16 17:26:24', 'E', 1, '2016-08-16 17:26:54', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.36'),
(4, 1, 1234567, 1, '2016-08-19 18:45:53', 'E', 1, '2016-08-19 18:46:23', '143.54.235.129', 'Mozilla/5.0 (Linux; Android 5.0; SM-G900M Build/LRX21T) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/52.0.2743.98 Mobile Safari/537.36'),
(5, 4, 121212, 1, '2016-08-24 08:07:32', 'E', 4, '2016-08-29 17:08:02', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/52.0.2743.116 Safari/537.36'),
(6, 4, 121212, 1, '2016-08-24 16:21:38', 'S', 4, '2016-08-29 17:08:08', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/52.0.2743.116 Safari/537.36'),
(7, 4, 121212, 1, '2016-08-25 07:43:44', 'E', 4, '2016-08-29 17:08:14', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/52.0.2743.116 Safari/537.36'),
(8, 4, 121212, 1, '2016-08-25 17:17:50', 'S', 4, '2016-08-29 17:08:20', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/52.0.2743.116 Safari/537.36'),
(9, 4, 121212, 1, '2016-08-29 08:20:57', 'E', 4, '2016-08-29 17:08:27', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/52.0.2743.116 Safari/537.36'),
(10, 1, 1234567, 1, '2016-04-01 07:55:00', 'E', 1, '2016-04-01 07:55:18', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.36'),
(11, 4, 121212, 1, '2016-04-01 09:09:00', 'E', 4, '2016-04-01 09:09:48', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.37'),
(13, 1, 1234567, 1, '2016-04-01 12:23:00', 'S', 1, '2016-04-01 12:23:04', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.39'),
(15, 1, 1234567, 1, '2016-04-01 13:11:00', 'E', 1, '2016-04-01 13:11:30', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.41'),
(16, 4, 121212, 1, '2016-04-01 13:22:00', 'S', 4, '2016-04-01 13:22:17', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.42'),
(18, 1, 1234567, 1, '2016-04-01 17:46:00', 'S', 1, '2016-04-01 17:46:42', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.44'),
(21, 4, 121212, 1, '2016-04-04 11:33:00', 'E', 4, '2016-04-04 11:33:52', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.47'),
(23, 1, 1234567, 1, '2016-04-04 13:06:00', 'E', 1, '2016-04-04 13:06:49', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.49'),
(24, 2, 123123, 1, '2016-04-04 13:09:00', 'E', 2, '2016-04-04 13:09:54', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.50'),
(26, 4, 121212, 1, '2016-04-04 17:08:00', 'S', 4, '2016-04-04 17:08:54', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.52'),
(27, 2, 123123, 1, '2016-04-04 17:52:00', 'S', 2, '2016-04-04 17:52:32', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.53'),
(29, 2, 123123, 1, '2016-04-04 18:24:00', 'E', 2, '2016-04-04 18:24:16', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.55'),
(30, 1, 1234567, 1, '2016-04-04 18:52:00', 'S', 1, '2016-04-04 18:52:31', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.56'),
(31, 2, 123123, 1, '2016-04-04 19:40:00', 'S', 2, '2016-04-04 19:40:09', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.57'),
(32, 1, 1234567, 1, '2016-04-05 08:09:00', 'E', 1, '2016-04-05 08:09:41', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.58'),
(33, 4, 121212, 1, '2016-04-05 09:01:00', 'E', 4, '2016-04-05 09:01:58', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.59'),
(35, 2, 123123, 1, '2016-04-05 10:39:00', 'E', 2, '2016-04-05 10:39:20', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.61'),
(37, 1, 1234567, 1, '2016-04-05 12:15:00', 'S', 1, '2016-04-05 12:15:51', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.63'),
(39, 2, 123123, 1, '2016-04-05 12:50:00', 'S', 2, '2016-04-05 12:50:33', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.65'),
(40, 4, 121212, 1, '2016-04-05 13:23:00', 'S', 4, '2016-04-05 13:23:28', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.66'),
(41, 1, 1234567, 1, '2016-04-05 13:25:00', 'E', 1, '2016-04-05 13:25:49', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.67'),
(42, 2, 123123, 1, '2016-04-05 13:54:00', 'E', 2, '2016-04-05 13:54:34', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.68'),
(43, 4, 121212, 1, '2016-04-05 14:42:00', 'E', 4, '2016-04-05 14:42:07', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.69'),
(44, 4, 121212, 1, '2016-04-05 17:17:00', 'S', 4, '2016-04-05 17:17:14', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.70'),
(46, 1, 1234567, 1, '2016-04-05 18:47:00', 'S', 1, '2016-04-05 18:47:20', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.72'),
(47, 2, 123123, 1, '2016-04-05 19:55:00', 'S', 2, '2016-04-05 19:55:58', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.73'),
(49, 1, 1234567, 1, '2016-04-06 08:15:00', 'E', 1, '2016-04-06 08:15:09', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.75'),
(50, 2, 123123, 1, '2016-04-06 10:05:00', 'E', 2, '2016-04-06 10:05:26', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.76'),
(51, 4, 121212, 1, '2016-04-06 10:16:00', 'E', 4, '2016-04-06 10:16:18', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.77'),
(53, 2, 123123, 1, '2016-04-06 12:58:00', 'S', 2, '2016-04-06 12:58:56', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.79'),
(54, 2, 123123, 1, '2016-04-06 13:25:00', 'E', 2, '2016-04-06 13:25:56', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.80'),
(56, 1, 1234567, 1, '2016-04-06 13:36:00', 'S', 1, '2016-04-06 13:36:47', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.82'),
(57, 4, 121212, 1, '2016-04-06 13:36:00', 'S', 4, '2016-04-06 13:36:48', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.83'),
(58, 1, 1234567, 1, '2016-04-06 14:32:00', 'E', 1, '2016-04-06 14:32:36', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.84'),
(59, 4, 121212, 1, '2016-04-06 14:32:00', 'E', 4, '2016-04-06 14:32:42', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.85'),
(60, 4, 121212, 1, '2016-04-06 15:34:00', 'S', 4, '2016-04-06 15:34:02', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.86'),
(62, 1, 1234567, 1, '2016-04-06 18:58:00', 'S', 1, '2016-04-06 18:58:09', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.88'),
(63, 2, 123123, 1, '2016-04-06 19:39:00', 'S', 2, '2016-04-06 19:39:10', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.89'),
(65, 1, 1234567, 1, '2016-04-07 08:07:00', 'E', 1, '2016-04-07 08:07:42', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.91'),
(66, 4, 121212, 1, '2016-04-07 10:04:00', 'E', 4, '2016-04-07 10:04:45', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.92'),
(67, 2, 123123, 1, '2016-04-07 10:37:00', 'E', 2, '2016-04-07 10:37:55', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.93'),
(70, 1, 1234567, 1, '2016-04-07 12:25:00', 'S', 1, '2016-04-07 12:25:30', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.96'),
(71, 2, 123123, 1, '2016-04-07 13:07:00', 'S', 2, '2016-04-07 13:07:52', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.97'),
(72, 1, 1234567, 1, '2016-04-07 13:23:00', 'E', 1, '2016-04-07 13:23:38', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.98'),
(73, 2, 123123, 1, '2016-04-07 13:35:00', 'E', 2, '2016-04-07 13:35:32', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.99'),
(74, 4, 121212, 1, '2016-04-07 14:29:00', 'S', 4, '2016-04-07 14:29:47', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.100'),
(76, 1, 1234567, 1, '2016-04-07 17:44:00', 'S', 1, '2016-04-07 17:44:58', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.102'),
(77, 2, 123123, 1, '2016-04-07 19:39:00', 'S', 2, '2016-04-07 19:39:32', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.103'),
(78, 1, 1234567, 1, '2016-04-08 08:20:00', 'E', 1, '2016-04-08 08:20:19', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.104'),
(80, 4, 121212, 1, '2016-04-08 08:51:00', 'E', 4, '2016-04-08 08:51:24', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.106'),
(81, 2, 123123, 1, '2016-04-08 10:07:00', 'E', 2, '2016-04-08 10:07:45', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.107'),
(84, 2, 123123, 1, '2016-04-08 12:08:00', 'S', 2, '2016-04-08 12:08:07', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.110'),
(85, 1, 1234567, 1, '2016-04-08 12:37:00', 'S', 1, '2016-04-08 12:37:51', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.111'),
(86, 4, 121212, 1, '2016-04-08 12:44:00', 'S', 4, '2016-04-08 12:44:10', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.112'),
(87, 2, 123123, 1, '2016-04-08 13:25:00', 'E', 2, '2016-04-08 13:25:35', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.113'),
(88, 1, 1234567, 1, '2016-04-08 13:36:00', 'E', 1, '2016-04-08 13:36:05', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.114'),
(89, 4, 121212, 1, '2016-04-08 13:51:00', 'E', 4, '2016-04-08 13:51:59', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.115'),
(90, 4, 121212, 1, '2016-04-08 17:14:00', 'S', 4, '2016-04-08 17:14:38', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.116'),
(92, 1, 1234567, 1, '2016-04-08 17:56:00', 'S', 1, '2016-04-08 17:56:45', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.118'),
(93, 2, 123123, 1, '2016-04-08 19:19:00', 'S', 2, '2016-04-08 19:19:18', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.119'),
(94, 4, 121212, 1, '2016-04-09 10:10:00', 'E', 4, '2016-04-09 10:10:25', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.120'),
(95, 4, 121212, 1, '2016-04-09 13:33:00', 'S', 4, '2016-04-09 13:33:36', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.121'),
(97, 2, 123123, 1, '2016-04-11 10:22:00', 'E', 2, '2016-04-11 10:22:53', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.123'),
(98, 4, 121212, 1, '2016-04-11 10:36:00', 'E', 4, '2016-04-11 10:36:30', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.124'),
(99, 2, 123123, 1, '2016-04-11 12:45:00', 'S', 2, '2016-04-11 12:45:48', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.125'),
(101, 2, 123123, 1, '2016-04-11 13:27:00', 'E', 2, '2016-04-11 13:27:43', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.127'),
(102, 1, 1234567, 1, '2016-04-11 13:33:00', 'E', 1, '2016-04-11 13:33:16', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.128'),
(103, 4, 121212, 1, '2016-04-11 13:59:00', 'S', 4, '2016-04-11 13:59:12', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.129'),
(105, 4, 121212, 1, '2016-04-11 14:42:00', 'E', 4, '2016-04-11 14:42:33', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.131'),
(107, 1, 1234567, 1, '2016-04-11 18:48:00', 'S', 1, '2016-04-11 18:48:35', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.133'),
(108, 2, 123123, 1, '2016-04-11 19:36:00', 'S', 2, '2016-04-11 19:36:39', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.134'),
(109, 4, 121212, 1, '2016-04-11 21:11:00', 'S', 4, '2016-04-11 21:11:27', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.135'),
(110, 1, 1234567, 1, '2016-04-12 07:58:00', 'E', 1, '2016-04-12 07:58:43', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.136'),
(112, 4, 121212, 1, '2016-04-12 09:15:00', 'E', 4, '2016-04-12 09:15:35', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.138'),
(113, 2, 123123, 1, '2016-04-12 10:38:00', 'E', 2, '2016-04-12 10:38:20', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.139'),
(116, 4, 121212, 1, '2016-04-12 13:03:00', 'S', 4, '2016-04-12 13:03:14', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.142'),
(117, 1, 1234567, 1, '2016-04-12 13:12:00', 'S', 1, '2016-04-12 13:12:29', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.143'),
(118, 2, 123123, 1, '2016-04-12 13:13:00', 'S', 2, '2016-04-12 13:13:29', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.144'),
(119, 2, 123123, 1, '2016-04-12 13:37:00', 'E', 2, '2016-04-12 13:37:57', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.145'),
(120, 1, 1234567, 1, '2016-04-12 13:38:00', 'E', 1, '2016-04-12 13:38:13', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.146'),
(122, 1, 1234567, 1, '2016-04-12 17:47:00', 'S', 1, '2016-04-12 17:47:44', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.148'),
(123, 2, 123123, 1, '2016-04-12 19:39:00', 'S', 2, '2016-04-12 19:39:06', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.149'),
(124, 1, 1234567, 1, '2016-04-13 07:53:00', 'E', 1, '2016-04-13 07:53:37', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.150'),
(126, 4, 121212, 1, '2016-04-13 10:38:00', 'E', 4, '2016-04-13 10:38:17', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.152'),
(127, 2, 123123, 1, '2016-04-13 10:49:00', 'E', 2, '2016-04-13 10:49:32', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.153'),
(128, 4, 121212, 1, '2016-04-13 11:43:00', 'S', 4, '2016-04-13 11:43:40', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.154'),
(129, 4, 121212, 1, '2016-04-13 12:51:00', 'E', 4, '2016-04-13 12:51:26', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.155'),
(130, 2, 123123, 1, '2016-04-13 13:09:00', 'S', 2, '2016-04-13 13:09:41', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.156'),
(132, 1, 1234567, 1, '2016-04-13 13:55:00', 'E', 1, '2016-04-13 13:55:46', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.158'),
(133, 2, 123123, 1, '2016-04-13 14:03:00', 'E', 2, '2016-04-13 14:03:32', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.159'),
(135, 4, 121212, 1, '2016-04-13 17:03:00', 'S', 4, '2016-04-13 17:03:13', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.161'),
(136, 2, 123123, 1, '2016-04-13 18:13:00', 'S', 2, '2016-04-13 18:13:43', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.162'),
(138, 2, 123123, 1, '2016-04-13 18:39:00', 'E', 2, '2016-04-13 18:39:26', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.164'),
(139, 1, 1234567, 1, '2016-04-13 18:59:00', 'S', 1, '2016-04-13 18:59:58', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.165'),
(140, 2, 123123, 1, '2016-04-13 19:39:00', 'S', 2, '2016-04-13 19:39:36', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.166'),
(141, 1, 1234567, 1, '2016-04-14 08:08:00', 'E', 1, '2016-04-14 08:08:10', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.167'),
(143, 4, 121212, 1, '2016-04-14 10:38:00', 'E', 4, '2016-04-14 10:38:13', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.169'),
(144, 2, 123123, 1, '2016-04-14 10:40:00', 'E', 2, '2016-04-14 10:40:11', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.170'),
(146, 1, 1234567, 1, '2016-04-14 12:18:00', 'S', 1, '2016-04-14 12:18:31', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.172'),
(148, 2, 123123, 1, '2016-04-14 13:06:00', 'S', 2, '2016-04-14 13:06:42', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.174'),
(149, 1, 1234567, 1, '2016-04-14 13:22:00', 'E', 1, '2016-04-14 13:22:34', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.175'),
(150, 2, 123123, 1, '2016-04-14 13:27:00', 'E', 2, '2016-04-14 13:27:01', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.176'),
(151, 4, 121212, 1, '2016-04-14 14:56:00', 'S', 4, '2016-04-14 14:56:13', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.177'),
(153, 1, 1234567, 1, '2016-04-14 17:34:00', 'S', 1, '2016-04-14 17:34:06', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.179'),
(154, 2, 123123, 1, '2016-04-14 18:58:00', 'S', 2, '2016-04-14 18:58:07', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.180'),
(155, 1, 1234567, 1, '2016-04-15 08:14:00', 'E', 1, '2016-04-15 08:14:19', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.181'),
(157, 2, 123123, 1, '2016-04-15 10:33:00', 'E', 2, '2016-04-15 10:33:32', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.183'),
(158, 4, 121212, 1, '2016-04-15 10:38:00', 'E', 4, '2016-04-15 10:38:06', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.184'),
(160, 1, 1234567, 1, '2016-04-15 11:37:00', 'S', 1, '2016-04-15 11:37:59', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.186'),
(161, 4, 121212, 1, '2016-04-15 12:04:00', 'S', 4, '2016-04-15 12:04:14', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.187'),
(162, 1, 1234567, 1, '2016-04-15 12:17:00', 'E', 1, '2016-04-15 12:17:23', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.188'),
(163, 4, 121212, 1, '2016-04-15 12:40:00', 'E', 4, '2016-04-15 12:40:33', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.189'),
(164, 2, 123123, 1, '2016-04-15 13:15:00', 'S', 2, '2016-04-15 13:15:10', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.190'),
(165, 2, 123123, 1, '2016-04-15 13:46:00', 'E', 2, '2016-04-15 13:46:20', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.191'),
(167, 1, 1234567, 1, '2016-04-15 16:52:00', 'S', 1, '2016-04-15 16:52:55', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.193'),
(168, 4, 121212, 1, '2016-04-15 17:35:00', 'S', 4, '2016-04-15 17:35:39', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.194'),
(170, 2, 123123, 1, '2016-04-15 18:58:00', 'S', 2, '2016-04-15 18:58:57', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.196'),
(172, 2, 123123, 1, '2016-04-18 10:37:00', 'E', 2, '2016-04-18 10:37:58', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.198'),
(173, 4, 121212, 1, '2016-04-18 10:56:00', 'E', 4, '2016-04-18 10:56:55', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.199'),
(175, 2, 123123, 1, '2016-04-18 12:37:00', 'S', 2, '2016-04-18 12:37:53', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.201'),
(177, 2, 123123, 1, '2016-04-18 13:25:00', 'E', 2, '2016-04-18 13:25:18', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.203'),
(178, 1, 1234567, 1, '2016-04-18 14:32:00', 'E', 1, '2016-04-18 14:32:19', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.204'),
(180, 4, 121212, 1, '2016-04-18 16:47:00', 'S', 4, '2016-04-18 16:47:22', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.206'),
(181, 1, 1234567, 1, '2016-04-18 17:33:00', 'S', 1, '2016-04-18 17:33:45', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.207'),
(182, 2, 123123, 1, '2016-04-18 19:00:00', 'S', 2, '2016-04-18 19:00:34', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.208'),
(184, 1, 1234567, 1, '2016-04-19 08:09:00', 'E', 1, '2016-04-19 08:09:42', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.210'),
(185, 4, 121212, 1, '2016-04-19 10:15:00', 'E', 4, '2016-04-19 10:15:29', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.211'),
(186, 2, 123123, 1, '2016-04-19 10:35:00', 'E', 2, '2016-04-19 10:35:52', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.212'),
(187, 1, 1234567, 1, '2016-04-19 12:11:00', 'S', 1, '2016-04-19 12:11:10', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.213'),
(189, 1, 1234567, 1, '2016-04-19 13:11:00', 'E', 1, '2016-04-19 13:11:35', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.215'),
(190, 2, 123123, 1, '2016-04-19 13:19:00', 'S', 2, '2016-04-19 13:19:10', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.216'),
(192, 4, 121212, 1, '2016-04-19 13:37:00', 'S', 4, '2016-04-19 13:37:19', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.218'),
(193, 2, 123123, 1, '2016-04-19 14:01:00', 'E', 2, '2016-04-19 14:01:50', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.219'),
(194, 4, 121212, 1, '2016-04-19 14:28:00', 'E', 4, '2016-04-19 14:28:04', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.220'),
(197, 4, 121212, 1, '2016-04-19 16:54:00', 'S', 4, '2016-04-19 16:54:21', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.223'),
(199, 1, 1234567, 1, '2016-04-19 19:14:00', 'S', 1, '2016-04-19 19:14:17', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.225'),
(200, 2, 123123, 1, '2016-04-19 19:40:00', 'S', 2, '2016-04-19 19:40:35', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.226'),
(201, 1, 1234567, 1, '2016-04-20 08:10:00', 'E', 1, '2016-04-20 08:10:12', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.227'),
(203, 4, 121212, 1, '2016-04-20 10:14:00', 'E', 4, '2016-04-20 10:14:03', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.229'),
(204, 2, 123123, 1, '2016-04-20 12:12:00', 'E', 2, '2016-04-20 12:12:36', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.230'),
(205, 1, 1234567, 1, '2016-04-20 13:04:00', 'S', 1, '2016-04-20 13:04:25', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.231'),
(207, 4, 121212, 1, '2016-04-20 13:04:00', 'S', 4, '2016-04-20 13:04:57', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.233'),
(208, 4, 121212, 1, '2016-04-20 14:12:00', 'E', 4, '2016-04-20 14:12:28', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.234'),
(210, 1, 1234567, 1, '2016-04-20 14:14:00', 'E', 1, '2016-04-20 14:14:10', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.236'),
(211, 4, 121212, 1, '2016-04-20 17:22:00', 'S', 4, '2016-04-20 17:22:24', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.237'),
(213, 2, 123123, 1, '2016-04-20 19:17:00', 'S', 2, '2016-04-20 19:17:05', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.239'),
(214, 1, 1234567, 1, '2016-04-20 19:29:00', 'S', 1, '2016-04-20 19:29:07', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.240'),
(215, 1, 1234567, 1, '2016-04-22 08:06:00', 'E', 1, '2016-04-22 08:06:10', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.241'),
(217, 4, 121212, 1, '2016-04-22 09:36:00', 'E', 4, '2016-04-22 09:36:08', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.243'),
(219, 1, 1234567, 1, '2016-04-22 12:11:00', 'S', 1, '2016-04-22 12:11:20', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.245'),
(220, 2, 123123, 1, '2016-04-22 12:29:00', 'E', 2, '2016-04-22 12:29:01', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.246'),
(221, 1, 1234567, 1, '2016-04-22 13:08:00', 'E', 1, '2016-04-22 13:08:19', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.247'),
(223, 4, 121212, 1, '2016-04-22 13:26:00', 'S', 4, '2016-04-22 13:26:40', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.249'),
(224, 4, 121212, 1, '2016-04-22 15:07:00', 'E', 4, '2016-04-22 15:07:25', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.250'),
(225, 4, 121212, 1, '2016-04-22 17:33:00', 'S', 4, '2016-04-22 17:33:30', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.251'),
(227, 1, 1234567, 1, '2016-04-22 18:06:00', 'S', 1, '2016-04-22 18:06:40', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.253'),
(228, 2, 123123, 1, '2016-04-22 19:35:00', 'S', 2, '2016-04-22 19:35:41', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.254'),
(230, 2, 123123, 1, '2016-04-25 11:13:00', 'E', 2, '2016-04-25 11:13:39', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.256'),
(232, 1, 1234567, 1, '2016-04-25 13:06:00', 'E', 1, '2016-04-25 13:06:44', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.258'),
(234, 4, 121212, 1, '2016-04-25 13:36:00', 'E', 4, '2016-04-25 13:36:26', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.260'),
(235, 4, 121212, 1, '2016-04-25 18:07:00', 'S', 4, '2016-04-25 18:07:38', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.261'),
(237, 1, 1234567, 1, '2016-04-25 19:15:00', 'S', 1, '2016-04-25 19:15:56', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.263'),
(238, 2, 123123, 1, '2016-04-25 19:58:00', 'S', 2, '2016-04-25 19:58:12', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.264'),
(240, 1, 1234567, 1, '2016-04-26 09:19:00', 'E', 1, '2016-04-26 09:20:00', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.266'),
(241, 2, 123123, 1, '2016-04-26 10:41:00', 'E', 2, '2016-04-26 10:41:21', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.267'),
(242, 4, 121212, 1, '2016-04-26 11:26:00', 'E', 4, '2016-04-26 11:26:13', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.268'),
(243, 1, 1234567, 1, '2016-04-26 12:30:00', 'S', 1, '2016-04-26 12:30:23', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.269'),
(245, 2, 123123, 1, '2016-04-26 13:09:00', 'S', 2, '2016-04-26 13:09:31', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.271'),
(247, 1, 1234567, 1, '2016-04-26 13:31:00', 'E', 1, '2016-04-26 13:31:08', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.273'),
(248, 2, 123123, 1, '2016-04-26 13:36:00', 'E', 2, '2016-04-26 13:36:24', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.274'),
(249, 4, 121212, 1, '2016-04-26 13:49:00', 'S', 4, '2016-04-26 13:49:53', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.275'),
(250, 4, 121212, 1, '2016-04-26 14:15:00', 'E', 4, '2016-04-26 14:15:39', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.276'),
(252, 4, 121212, 1, '2016-04-26 18:50:00', 'S', 4, '2016-04-26 18:50:35', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.278'),
(253, 1, 1234567, 1, '2016-04-26 19:08:00', 'S', 1, '2016-04-26 19:08:51', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.279'),
(254, 2, 123123, 1, '2016-04-26 19:40:00', 'S', 2, '2016-04-26 19:40:26', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.280'),
(255, 1, 1234567, 1, '2016-04-27 08:40:00', 'E', 1, '2016-04-27 08:40:52', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.281'),
(257, 2, 123123, 1, '2016-04-27 10:36:00', 'E', 2, '2016-04-27 10:36:32', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.283'),
(259, 4, 121212, 1, '2016-04-27 12:31:00', 'E', 4, '2016-04-27 12:31:16', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.285'),
(261, 1, 1234567, 1, '2016-04-27 13:14:00', 'S', 1, '2016-04-27 13:14:51', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.287'),
(262, 2, 123123, 1, '2016-04-27 13:28:00', 'S', 2, '2016-04-27 13:28:23', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.288'),
(263, 1, 1234567, 1, '2016-04-27 14:15:00', 'E', 1, '2016-04-27 14:15:59', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.289'),
(264, 2, 123123, 1, '2016-04-27 14:35:00', 'E', 2, '2016-04-27 14:35:14', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.290'),
(267, 4, 121212, 1, '2016-04-27 18:27:00', 'S', 4, '2016-04-27 18:27:51', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.293'),
(268, 1, 1234567, 1, '2016-04-27 18:47:00', 'S', 1, '2016-04-27 18:47:59', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.294'),
(270, 2, 123123, 1, '2016-04-27 19:29:00', 'S', 2, '2016-04-27 19:29:07', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.296'),
(272, 1, 1234567, 1, '2016-04-29 08:31:00', 'E', 1, '2016-04-29 08:31:21', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.298'),
(274, 1, 1234567, 1, '2016-04-29 12:39:00', 'S', 1, '2016-04-29 12:39:38', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.300'),
(275, 1, 1234567, 1, '2016-04-29 13:25:00', 'E', 1, '2016-04-29 13:25:41', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.301'),
(277, 2, 123123, 1, '2016-04-29 13:25:00', 'E', 2, '2016-04-29 13:25:56', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.303'),
(278, 4, 121212, 1, '2016-04-29 13:38:00', 'E', 4, '2016-04-29 13:38:43', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.304'),
(279, 4, 121212, 1, '2016-04-29 17:40:00', 'S', 4, '2016-04-29 17:40:56', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.305'),
(281, 2, 123123, 1, '2016-04-29 17:47:00', 'S', 2, '2016-04-29 17:47:31', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.307'),
(282, 2, 123123, 1, '2016-04-29 17:50:00', 'E', 2, '2016-04-29 17:50:22', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.308'),
(283, 1, 1234567, 1, '2016-04-29 18:02:00', 'S', 1, '2016-04-29 18:02:17', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.309'),
(284, 2, 123123, 1, '2016-04-29 19:12:00', 'S', 2, '2016-04-29 19:12:20', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.310'),
(286, 4, 121212, 1, '2016-05-02 09:44:00', 'E', 4, '2016-05-02 09:44:10', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.312'),
(287, 2, 123123, 1, '2016-05-02 10:35:00', 'E', 2, '2016-05-02 10:35:48', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.313'),
(289, 2, 123123, 1, '2016-05-02 12:49:00', 'S', 2, '2016-05-02 12:49:05', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.315'),
(290, 4, 121212, 1, '2016-05-02 12:59:00', 'S', 4, '2016-05-02 12:59:58', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.316'),
(292, 4, 121212, 1, '2016-05-02 13:51:00', 'E', 4, '2016-05-02 13:51:03', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.318'),
(293, 2, 123123, 1, '2016-05-02 14:18:00', 'E', 2, '2016-05-02 14:18:46', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.319'),
(294, 4, 121212, 1, '2016-05-02 16:34:00', 'S', 4, '2016-05-02 16:34:33', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.320'),
(296, 2, 123123, 1, '2016-05-02 18:16:00', 'S', 2, '2016-05-02 18:16:31', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.322'),
(299, 2, 123123, 1, '2016-05-03 12:02:00', 'E', 2, '2016-05-03 12:02:17', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.325'),
(301, 4, 121212, 1, '2016-05-03 12:18:00', 'E', 4, '2016-05-03 12:18:37', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.327'),
(303, 4, 121212, 1, '2016-05-03 18:41:00', 'S', 4, '2016-05-03 18:41:02', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.329'),
(304, 2, 123123, 1, '2016-05-03 19:02:00', 'S', 2, '2016-05-03 19:02:09', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.330'),
(306, 2, 123123, 1, '2016-05-04 12:31:00', 'E', 2, '2016-05-04 12:31:19', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.332'),
(307, 4, 121212, 1, '2016-05-04 13:05:00', 'E', 4, '2016-05-04 13:05:25', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.333'),
(308, 2, 123123, 1, '2016-05-04 13:11:00', 'S', 2, '2016-05-04 13:11:06', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.334'),
(310, 2, 123123, 1, '2016-05-04 13:48:00', 'E', 2, '2016-05-04 13:48:07', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.336'),
(313, 4, 121212, 1, '2016-05-04 17:47:00', 'S', 4, '2016-05-04 17:47:04', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.339'),
(314, 2, 123123, 1, '2016-05-04 19:35:00', 'S', 2, '2016-05-04 19:35:53', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.340'),
(315, 1, 1234567, 1, '2016-05-05 08:34:00', 'E', 1, '2016-05-05 08:34:40', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.341'),
(317, 2, 123123, 1, '2016-05-05 10:36:00', 'E', 2, '2016-05-05 10:36:49', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.343'),
(318, 4, 121212, 1, '2016-05-05 11:19:00', 'E', 4, '2016-05-05 11:19:04', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.344'),
(319, 2, 123123, 1, '2016-05-05 13:05:00', 'S', 2, '2016-05-05 13:05:16', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.345'),
(320, 4, 121212, 1, '2016-05-05 13:05:00', 'S', 4, '2016-05-05 13:05:29', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.346'),
(322, 1, 1234567, 1, '2016-05-05 13:15:00', 'S', 1, '2016-05-05 13:15:51', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.348'),
(323, 2, 123123, 1, '2016-05-05 13:37:00', 'E', 2, '2016-05-05 13:37:55', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.349'),
(324, 4, 121212, 1, '2016-05-05 13:37:00', 'E', 4, '2016-05-05 13:37:56', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.350'),
(326, 1, 1234567, 1, '2016-05-05 14:15:00', 'E', 1, '2016-05-05 14:15:12', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.352'),
(327, 4, 121212, 1, '2016-05-05 15:08:00', 'S', 4, '2016-05-05 15:08:33', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.353'),
(329, 4, 121212, 1, '2016-05-05 17:15:00', 'E', 4, '2016-05-05 17:15:51', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.355'),
(330, 1, 1234567, 1, '2016-05-05 17:39:00', 'S', 1, '2016-05-05 17:39:59', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.356'),
(331, 4, 121212, 1, '2016-05-05 18:10:00', 'S', 4, '2016-05-05 18:10:09', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.357'),
(332, 2, 123123, 1, '2016-05-05 19:39:00', 'S', 2, '2016-05-05 19:39:33', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.358'),
(334, 1, 1234567, 1, '2016-05-06 08:32:00', 'E', 1, '2016-05-06 08:32:00', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.360'),
(335, 2, 123123, 1, '2016-05-06 10:42:00', 'E', 2, '2016-05-06 10:42:10', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.361'),
(336, 1, 1234567, 1, '2016-05-06 11:11:00', 'S', 1, '2016-05-06 11:11:16', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.362'),
(338, 4, 121212, 1, '2016-05-06 12:14:00', 'E', 4, '2016-05-06 12:14:43', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.364'),
(339, 2, 123123, 1, '2016-05-06 12:45:00', 'S', 2, '2016-05-06 12:45:33', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.365'),
(340, 1, 1234567, 1, '2016-05-06 12:48:00', 'E', 1, '2016-05-06 12:48:19', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.366'),
(341, 4, 121212, 1, '2016-05-06 13:12:00', 'S', 4, '2016-05-06 13:12:51', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.367'),
(342, 4, 121212, 1, '2016-05-06 13:43:00', 'E', 4, '2016-05-06 13:43:11', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.368'),
(343, 2, 123123, 1, '2016-05-06 13:58:00', 'E', 2, '2016-05-06 13:58:02', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.369'),
(345, 1, 1234567, 1, '2016-05-06 17:45:00', 'S', 1, '2016-05-06 17:45:33', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.371'),
(347, 4, 121212, 1, '2016-05-06 18:35:00', 'S', 4, '2016-05-06 18:35:58', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.373'),
(348, 2, 123123, 1, '2016-05-06 19:27:00', 'S', 2, '2016-05-06 19:27:52', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.374'),
(350, 1, 1234567, 1, '2016-05-09 09:16:00', 'E', 1, '2016-05-09 09:16:55', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.376'),
(351, 4, 121212, 1, '2016-05-09 10:14:00', 'E', 4, '2016-05-09 10:14:59', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.377'),
(352, 4, 121212, 1, '2016-05-09 11:41:00', 'S', 4, '2016-05-09 11:41:00', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.378'),
(353, 1, 1234567, 1, '2016-05-09 11:41:00', 'S', 1, '2016-05-09 11:41:04', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.379'),
(354, 2, 123123, 1, '2016-05-09 11:46:00', 'E', 2, '2016-05-09 11:46:07', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.380'),
(355, 4, 121212, 1, '2016-05-09 12:37:00', 'E', 4, '2016-05-09 12:37:31', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.381'),
(356, 1, 1234567, 1, '2016-05-09 12:37:00', 'E', 1, '2016-05-09 12:37:36', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.382');
INSERT INTO `ponto` (`nr_ponto`, `id_pessoa`, `matricula`, `nr_vinculo`, `data_hora_ponto`, `entrada_saida`, `id_pessoa_registro`, `data_hora_registro`, `ip_registro`, `ambiente_registro`) VALUES
(359, 4, 121212, 1, '2016-05-09 16:50:00', 'S', 4, '2016-05-09 16:50:41', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.385'),
(361, 1, 1234567, 1, '2016-05-09 17:37:00', 'S', 1, '2016-05-09 17:37:48', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.387'),
(362, 2, 123123, 1, '2016-05-09 18:46:00', 'S', 2, '2016-05-09 18:46:26', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.388'),
(363, 1, 1234567, 1, '2016-05-10 08:12:00', 'E', 1, '2016-05-10 08:12:17', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.389'),
(365, 4, 121212, 1, '2016-05-10 10:36:00', 'E', 4, '2016-05-10 10:36:02', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.391'),
(366, 2, 123123, 1, '2016-05-10 11:18:00', 'E', 2, '2016-05-10 11:18:25', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.392'),
(367, 1, 1234567, 1, '2016-05-10 12:25:00', 'S', 1, '2016-05-10 12:25:29', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.393'),
(370, 2, 123123, 1, '2016-05-10 13:38:00', 'S', 2, '2016-05-10 13:38:43', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.396'),
(371, 1, 1234567, 1, '2016-05-10 13:44:00', 'E', 1, '2016-05-10 13:44:10', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.397'),
(372, 2, 123123, 1, '2016-05-10 14:39:00', 'E', 2, '2016-05-10 14:39:22', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.398'),
(373, 4, 121212, 1, '2016-05-10 15:14:00', 'S', 4, '2016-05-10 15:14:21', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.399'),
(375, 1, 1234567, 1, '2016-05-10 18:58:00', 'S', 1, '2016-05-10 18:58:36', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.401'),
(376, 2, 123123, 1, '2016-05-10 19:40:00', 'S', 2, '2016-05-10 19:40:58', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.402'),
(378, 1, 1234567, 1, '2016-05-11 08:44:00', 'E', 1, '2016-05-11 08:44:47', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.404'),
(379, 2, 123123, 1, '2016-05-11 10:41:00', 'E', 2, '2016-05-11 10:41:32', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.405'),
(380, 4, 121212, 1, '2016-05-11 12:22:00', 'E', 4, '2016-05-11 12:22:31', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.406'),
(381, 2, 123123, 1, '2016-05-11 13:10:00', 'S', 2, '2016-05-11 13:10:59', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.407'),
(382, 1, 1234567, 1, '2016-05-11 13:11:00', 'S', 1, '2016-05-11 13:11:28', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.408'),
(384, 2, 123123, 1, '2016-05-11 13:45:00', 'E', 2, '2016-05-11 13:45:53', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.410'),
(385, 1, 1234567, 1, '2016-05-11 13:46:00', 'E', 1, '2016-05-11 13:46:13', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.411'),
(387, 4, 121212, 1, '2016-05-11 17:13:00', 'S', 4, '2016-05-11 17:13:40', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.413'),
(389, 1, 1234567, 1, '2016-05-11 19:13:00', 'S', 1, '2016-05-11 19:13:15', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.415'),
(390, 2, 123123, 1, '2016-05-11 19:30:00', 'S', 2, '2016-05-11 19:30:12', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.416'),
(391, 1, 1234567, 1, '2016-05-12 08:01:00', 'E', 1, '2016-05-12 08:01:59', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.417'),
(393, 4, 121212, 1, '2016-05-12 09:28:00', 'E', 4, '2016-05-12 09:28:43', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.419'),
(394, 2, 123123, 1, '2016-05-12 10:35:00', 'E', 2, '2016-05-12 10:35:25', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.420'),
(396, 1, 1234567, 1, '2016-05-12 12:44:00', 'S', 1, '2016-05-12 12:44:10', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.422'),
(397, 2, 123123, 1, '2016-05-12 12:58:00', 'S', 2, '2016-05-12 12:58:12', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.423'),
(398, 2, 123123, 1, '2016-05-12 13:30:00', 'E', 2, '2016-05-12 13:30:16', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.424'),
(400, 1, 1234567, 1, '2016-05-12 13:31:00', 'E', 1, '2016-05-12 13:31:59', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.426'),
(401, 4, 121212, 1, '2016-05-12 14:33:00', 'S', 4, '2016-05-12 14:33:40', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.427'),
(403, 1, 1234567, 1, '2016-05-12 17:45:00', 'S', 1, '2016-05-12 17:45:43', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.429'),
(404, 2, 123123, 1, '2016-05-12 19:29:00', 'S', 2, '2016-05-12 19:29:15', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.430'),
(406, 1, 1234567, 1, '2016-05-13 09:43:00', 'E', 1, '2016-05-13 09:43:36', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.432'),
(407, 2, 123123, 1, '2016-05-13 10:35:00', 'E', 2, '2016-05-13 10:35:33', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.433'),
(409, 1, 1234567, 1, '2016-05-13 11:28:00', 'S', 1, '2016-05-13 11:28:34', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.435'),
(410, 4, 121212, 1, '2016-05-13 11:42:00', 'E', 4, '2016-05-13 11:42:04', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.436'),
(412, 1, 1234567, 1, '2016-05-13 12:07:00', 'E', 1, '2016-05-13 12:07:26', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.438'),
(413, 2, 123123, 1, '2016-05-13 13:01:00', 'S', 2, '2016-05-13 13:01:05', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.439'),
(414, 4, 121212, 1, '2016-05-13 13:14:00', 'S', 4, '2016-05-13 13:14:35', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.440'),
(415, 2, 123123, 1, '2016-05-13 13:23:00', 'E', 2, '2016-05-13 13:23:31', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.441'),
(416, 4, 121212, 1, '2016-05-13 13:33:00', 'E', 4, '2016-05-13 13:33:25', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.442'),
(417, 4, 121212, 1, '2016-05-13 15:37:00', 'S', 4, '2016-05-13 15:37:31', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.443'),
(418, 4, 121212, 1, '2016-05-13 16:17:00', 'E', 4, '2016-05-13 16:17:19', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.444'),
(420, 1, 1234567, 1, '2016-05-13 18:13:00', 'S', 1, '2016-05-13 18:13:04', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.446'),
(421, 4, 121212, 1, '2016-05-13 18:57:00', 'S', 4, '2016-05-13 18:57:37', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.447'),
(422, 2, 123123, 1, '2016-05-13 18:59:00', 'S', 2, '2016-05-13 18:59:13', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.448'),
(424, 4, 121212, 1, '2016-05-16 12:56:00', 'E', 4, '2016-05-16 12:56:23', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.450'),
(425, 1, 1234567, 1, '2016-05-16 12:56:00', 'E', 1, '2016-05-16 12:56:24', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.451'),
(428, 2, 123123, 1, '2016-05-16 13:57:00', 'E', 2, '2016-05-16 13:57:30', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.454'),
(429, 4, 121212, 1, '2016-05-16 17:16:00', 'S', 4, '2016-05-16 17:16:08', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.455'),
(431, 1, 1234567, 1, '2016-05-16 18:40:00', 'S', 1, '2016-05-16 18:40:03', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.457'),
(432, 2, 123123, 1, '2016-05-16 19:59:00', 'S', 2, '2016-05-16 19:59:26', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.458'),
(433, 1, 1234567, 1, '2016-05-17 08:07:00', 'E', 1, '2016-05-17 08:07:59', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.459'),
(435, 4, 121212, 1, '2016-05-17 08:47:00', 'E', 4, '2016-05-17 08:47:54', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.461'),
(436, 2, 123123, 1, '2016-05-17 10:38:00', 'E', 2, '2016-05-17 10:38:14', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.462'),
(439, 1, 1234567, 1, '2016-05-17 12:27:00', 'S', 1, '2016-05-17 12:27:36', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.465'),
(440, 4, 121212, 1, '2016-05-17 12:36:00', 'S', 4, '2016-05-17 12:36:10', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.466'),
(441, 2, 123123, 1, '2016-05-17 12:56:00', 'S', 2, '2016-05-17 12:56:04', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.467'),
(442, 2, 123123, 1, '2016-05-17 13:16:00', 'E', 2, '2016-05-17 13:16:50', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.468'),
(443, 1, 1234567, 1, '2016-05-17 13:54:00', 'E', 1, '2016-05-17 13:54:59', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.469'),
(445, 1, 1234567, 1, '2016-05-17 19:29:00', 'S', 1, '2016-05-17 19:29:42', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.471'),
(446, 2, 123123, 1, '2016-05-17 19:59:00', 'S', 2, '2016-05-17 19:59:04', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.472'),
(447, 1, 1234567, 1, '2016-05-18 08:34:00', 'E', 1, '2016-05-18 08:34:17', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.473'),
(449, 4, 121212, 1, '2016-05-18 10:36:00', 'E', 4, '2016-05-18 10:36:46', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.475'),
(450, 2, 123123, 1, '2016-05-18 10:59:00', 'E', 2, '2016-05-18 10:59:34', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.476'),
(451, 2, 123123, 1, '2016-05-18 12:47:00', 'S', 2, '2016-05-18 12:47:22', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.477'),
(453, 1, 1234567, 1, '2016-05-18 12:55:00', 'S', 1, '2016-05-18 12:55:40', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.479'),
(455, 1, 1234567, 1, '2016-05-18 13:42:00', 'E', 1, '2016-05-18 13:42:12', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.481'),
(456, 2, 123123, 1, '2016-05-18 14:03:00', 'E', 2, '2016-05-18 14:03:45', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.482'),
(457, 4, 121212, 1, '2016-05-18 16:19:00', 'S', 4, '2016-05-18 16:19:02', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.483'),
(459, 2, 123123, 1, '2016-05-18 18:42:00', 'S', 2, '2016-05-18 18:42:07', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.485'),
(460, 1, 1234567, 1, '2016-05-18 19:15:00', 'S', 1, '2016-05-18 19:15:36', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.486'),
(462, 1, 1234567, 1, '2016-05-19 08:35:00', 'E', 1, '2016-05-19 08:35:57', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.488'),
(463, 2, 123123, 1, '2016-05-19 10:37:00', 'E', 2, '2016-05-19 10:37:18', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.489'),
(466, 1, 1234567, 1, '2016-05-19 12:35:00', 'S', 1, '2016-05-19 12:35:01', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.492'),
(467, 2, 123123, 1, '2016-05-19 13:11:00', 'S', 2, '2016-05-19 13:11:20', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.493'),
(468, 2, 123123, 1, '2016-05-19 13:31:00', 'E', 2, '2016-05-19 13:31:32', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.494'),
(469, 1, 1234567, 1, '2016-05-19 13:55:00', 'E', 1, '2016-05-19 13:55:03', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.495'),
(471, 1, 1234567, 1, '2016-05-19 18:10:00', 'S', 1, '2016-05-19 18:10:31', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.497'),
(472, 2, 123123, 1, '2016-05-19 19:24:00', 'S', 2, '2016-05-19 19:24:09', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.498'),
(473, 1, 1234567, 1, '2016-05-20 08:32:00', 'E', 1, '2016-05-20 08:32:24', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.499'),
(474, 2, 123123, 1, '2016-05-20 09:01:00', 'E', 2, '2016-05-20 09:01:25', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.500'),
(476, 4, 121212, 1, '2016-05-20 11:17:00', 'E', 4, '2016-05-20 11:17:27', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.502'),
(478, 2, 123123, 1, '2016-05-20 12:31:00', 'S', 2, '2016-05-20 12:31:44', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.504'),
(479, 1, 1234567, 1, '2016-05-20 12:33:00', 'S', 1, '2016-05-20 12:33:15', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.505'),
(480, 2, 123123, 1, '2016-05-20 13:04:00', 'E', 2, '2016-05-20 13:04:34', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.506'),
(482, 4, 121212, 1, '2016-05-20 13:58:00', 'S', 4, '2016-05-20 13:58:22', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.508'),
(483, 4, 121212, 1, '2016-05-20 15:53:00', 'E', 4, '2016-05-20 15:53:37', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.509'),
(485, 2, 123123, 1, '2016-05-20 19:01:00', 'S', 2, '2016-05-20 19:01:09', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.511'),
(486, 4, 121212, 1, '2016-05-20 19:12:00', 'S', 4, '2016-05-20 19:12:14', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.512'),
(488, 2, 123123, 1, '2016-05-23 11:11:00', 'E', 2, '2016-05-23 11:11:16', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.514'),
(489, 4, 121212, 1, '2016-05-23 11:11:00', 'E', 4, '2016-05-23 11:11:39', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.515'),
(490, 2, 123123, 1, '2016-05-23 12:37:00', 'S', 2, '2016-05-23 12:37:06', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.516'),
(493, 2, 123123, 1, '2016-05-23 14:04:00', 'E', 2, '2016-05-23 14:04:38', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.519'),
(495, 2, 123123, 1, '2016-05-23 19:08:00', 'S', 2, '2016-05-23 19:08:35', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.521'),
(496, 4, 121212, 1, '2016-05-23 21:13:00', 'S', 4, '2016-05-23 21:13:08', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.522'),
(498, 2, 123123, 1, '2016-05-24 09:27:00', 'E', 2, '2016-05-24 09:27:47', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.524'),
(500, 4, 121212, 1, '2016-05-24 12:07:00', 'E', 4, '2016-05-24 12:07:09', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.526'),
(502, 2, 123123, 1, '2016-05-24 12:57:00', 'S', 2, '2016-05-24 12:57:24', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.528'),
(503, 2, 123123, 1, '2016-05-24 13:59:00', 'E', 2, '2016-05-24 13:59:19', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.529'),
(504, 4, 121212, 1, '2016-05-24 14:48:00', 'S', 4, '2016-05-24 14:48:58', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.530'),
(506, 2, 123123, 1, '2016-05-24 19:16:00', 'S', 2, '2016-05-24 19:16:27', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.532'),
(509, 4, 121212, 1, '2016-05-25 12:59:00', 'E', 4, '2016-05-25 12:59:45', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.535'),
(511, 2, 123123, 1, '2016-05-25 13:45:00', 'E', 2, '2016-05-25 13:45:25', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.537'),
(512, 4, 121212, 1, '2016-05-25 14:37:00', 'S', 4, '2016-05-25 14:37:51', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.538'),
(515, 2, 123123, 1, '2016-05-27 10:37:00', 'E', 2, '2016-05-27 10:37:22', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.541'),
(516, 2, 123123, 1, '2016-05-27 12:52:00', 'S', 2, '2016-05-27 12:52:36', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.542'),
(518, 4, 121212, 1, '2016-05-27 13:28:00', 'E', 4, '2016-05-27 13:28:37', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.544'),
(519, 2, 123123, 1, '2016-05-27 13:36:00', 'E', 2, '2016-05-27 13:36:58', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.545'),
(522, 4, 121212, 1, '2016-05-27 17:22:00', 'S', 4, '2016-05-27 17:22:04', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.548'),
(523, 2, 123123, 1, '2016-05-27 19:40:00', 'S', 2, '2016-05-27 19:40:17', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.549'),
(527, 2, 123123, 1, '2016-05-30 13:50:00', 'E', 2, '2016-05-30 13:50:57', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.553'),
(528, 4, 121212, 1, '2016-05-30 13:58:00', 'E', 4, '2016-05-30 13:58:37', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.554'),
(530, 2, 123123, 1, '2016-05-30 19:39:00', 'S', 2, '2016-05-30 19:39:07', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.556'),
(531, 4, 121212, 1, '2016-05-30 21:48:00', 'S', 4, '2016-05-30 21:48:36', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.557'),
(533, 2, 123123, 1, '2016-05-31 10:46:00', 'E', 2, '2016-05-31 10:46:41', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.559'),
(534, 4, 121212, 1, '2016-05-31 11:21:00', 'E', 4, '2016-05-31 11:21:39', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.560'),
(537, 4, 121212, 1, '2016-05-31 14:10:00', 'S', 4, '2016-05-31 14:10:32', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.563'),
(539, 2, 123123, 1, '2016-05-31 19:39:00', 'S', 2, '2016-05-31 19:39:44', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.565'),
(540, 2, 123123, 1, '2016-06-01 10:47:00', 'E', 2, '2016-06-01 10:47:38', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.566'),
(541, 2, 123123, 1, '2016-06-01 12:46:00', 'S', 2, '2016-06-01 12:46:55', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.567'),
(542, 2, 123123, 1, '2016-06-01 13:31:00', 'E', 2, '2016-06-01 13:31:33', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.568'),
(543, 4, 121212, 1, '2016-06-01 14:02:00', 'E', 4, '2016-06-01 14:02:34', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.569'),
(544, 2, 123123, 1, '2016-06-01 18:30:00', 'S', 2, '2016-06-01 18:30:09', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.570'),
(545, 2, 123123, 1, '2016-06-01 18:49:00', 'E', 2, '2016-06-01 18:49:53', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.571'),
(546, 2, 123123, 1, '2016-06-01 20:05:00', 'S', 2, '2016-06-01 20:05:15', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.572'),
(547, 4, 121212, 1, '2016-06-01 20:30:00', 'S', 4, '2016-06-01 20:30:54', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.573'),
(548, 2, 123123, 1, '2016-06-02 10:27:00', 'E', 2, '2016-06-02 10:27:50', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.574'),
(549, 2, 123123, 1, '2016-06-02 13:15:00', 'S', 2, '2016-06-02 13:15:12', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.575'),
(550, 4, 121212, 1, '2016-06-02 13:26:00', 'E', 4, '2016-06-02 13:26:16', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.576'),
(551, 2, 123123, 1, '2016-06-02 13:53:00', 'E', 2, '2016-06-02 13:53:59', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.577'),
(552, 4, 121212, 1, '2016-06-02 15:25:00', 'S', 4, '2016-06-02 15:25:19', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.578'),
(553, 2, 123123, 1, '2016-06-02 18:57:00', 'S', 2, '2016-06-02 18:57:52', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.579'),
(554, 4, 121212, 1, '2016-06-03 11:21:00', 'E', 4, '2016-06-03 11:21:48', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.580'),
(555, 2, 123123, 1, '2016-06-03 12:08:00', 'E', 2, '2016-06-03 12:08:46', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.581'),
(556, 4, 121212, 1, '2016-06-03 17:18:00', 'S', 4, '2016-06-03 17:18:35', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.582'),
(557, 2, 123123, 1, '2016-06-03 18:30:00', 'S', 2, '2016-06-03 18:30:51', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.583'),
(558, 2, 123123, 1, '2016-06-06 08:06:00', 'E', 2, '2016-06-06 08:06:30', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.584'),
(559, 2, 123123, 1, '2016-06-06 12:00:00', 'S', 2, '2016-06-06 12:01:00', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.585'),
(560, 1, 1234567, 1, '2016-06-06 13:02:00', 'E', 1, '2016-06-06 13:02:24', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.586'),
(561, 2, 123123, 1, '2016-06-06 13:49:00', 'E', 2, '2016-06-06 13:49:50', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.587'),
(562, 4, 121212, 1, '2016-06-06 14:08:00', 'E', 4, '2016-06-06 14:08:01', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.588'),
(563, 4, 121212, 1, '2016-06-06 17:34:00', 'S', 4, '2016-06-06 17:34:21', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.589'),
(564, 2, 123123, 1, '2016-06-06 18:59:00', 'S', 2, '2016-06-06 18:59:41', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.590'),
(565, 1, 1234567, 1, '2016-06-06 19:19:00', 'S', 1, '2016-06-06 19:19:13', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.591'),
(566, 1, 1234567, 1, '2016-06-07 08:32:00', 'E', 1, '2016-06-07 08:32:06', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.592'),
(567, 2, 123123, 1, '2016-06-07 10:03:00', 'E', 2, '2016-06-07 10:03:33', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.593'),
(568, 2, 123123, 1, '2016-06-07 11:13:00', 'S', 2, '2016-06-07 11:13:16', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.594'),
(569, 4, 121212, 1, '2016-06-07 11:19:00', 'E', 4, '2016-06-07 11:19:55', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.595'),
(570, 2, 123123, 1, '2016-06-07 12:14:00', 'E', 2, '2016-06-07 12:14:30', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.596'),
(571, 1, 1234567, 1, '2016-06-07 12:24:00', 'S', 1, '2016-06-07 12:24:48', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.597'),
(572, 4, 121212, 1, '2016-06-07 13:16:00', 'S', 4, '2016-06-07 13:16:45', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.598'),
(573, 1, 1234567, 1, '2016-06-07 13:39:00', 'E', 1, '2016-06-07 13:39:54', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.599'),
(574, 4, 121212, 1, '2016-06-07 13:57:00', 'E', 4, '2016-06-07 13:57:39', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.600'),
(575, 4, 121212, 1, '2016-06-07 15:35:00', 'S', 4, '2016-06-07 15:35:17', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.601'),
(576, 1, 1234567, 1, '2016-06-07 19:39:00', 'S', 1, '2016-06-07 19:39:57', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.602'),
(577, 2, 123123, 1, '2016-06-07 19:40:00', 'S', 2, '2016-06-07 19:40:15', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.603'),
(578, 2, 123123, 1, '2016-06-16 15:39:00', 'E', 2, '2016-06-16 15:39:50', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.604'),
(579, 2, 123123, 1, '2016-06-16 15:40:00', 'E', 2, '2016-06-16 15:40:03', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.605'),
(580, 1, 1234567, 1, '2016-07-06 14:22:00', 'E', 1, '2016-07-06 14:22:00', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.606'),
(581, 1, 1234567, 1, '2016-07-06 12:39:00', 'E', 1, '2016-07-06 12:39:29', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.607'),
(582, 1, 1234567, 1, '2016-07-01 09:03:00', 'E', 1, '2016-07-01 09:03:41', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.608'),
(583, 1, 1234567, 1, '2016-07-01 12:34:00', 'S', 1, '2016-07-01 12:34:33', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.609'),
(584, 1, 1234567, 1, '2016-07-01 14:11:00', 'E', 1, '2016-07-01 14:11:54', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.610'),
(585, 1, 1234567, 1, '2016-07-04 11:05:00', 'E', 1, '2016-07-04 11:05:30', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.611'),
(586, 1, 1234567, 1, '2016-07-04 12:23:00', 'S', 1, '2016-07-04 12:23:06', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.612'),
(587, 1, 1234567, 1, '2016-07-04 13:37:00', 'E', 1, '2016-07-04 13:37:37', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.613'),
(588, 1, 1234567, 1, '2016-07-04 19:27:00', 'S', 1, '2016-07-04 19:27:30', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.614'),
(589, 1, 1234567, 1, '2016-07-05 08:56:00', 'E', 1, '2016-07-05 08:56:48', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.615'),
(590, 1, 1234567, 1, '2016-07-05 12:14:00', 'S', 1, '2016-07-05 12:14:19', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.616'),
(591, 1, 1234567, 1, '2016-07-05 13:33:00', 'E', 1, '2016-07-05 13:33:16', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.617'),
(592, 1, 1234567, 1, '2016-07-05 19:15:00', 'S', 1, '2016-07-05 19:15:05', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.618'),
(593, 1, 1234567, 1, '2016-07-06 12:04:00', 'S', 1, '2016-07-06 12:04:43', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.619'),
(594, 1, 1234567, 1, '2016-07-06 19:11:00', 'S', 1, '2016-07-06 19:11:16', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.620'),
(595, 1, 1234567, 1, '2016-07-07 08:46:00', 'E', 1, '2016-07-07 08:46:58', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.621'),
(596, 1, 1234567, 1, '2016-07-07 12:41:00', 'S', 1, '2016-07-07 12:41:40', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.622'),
(597, 1, 1234567, 1, '2016-07-07 13:48:00', 'E', 1, '2016-07-07 13:48:25', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.623'),
(598, 1, 1234567, 1, '2016-07-07 17:37:00', 'S', 1, '2016-07-07 17:37:21', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.624'),
(599, 1, 1234567, 1, '2016-07-08 12:57:00', 'E', 1, '2016-07-08 12:57:43', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.625'),
(600, 1, 1234567, 1, '2016-07-08 13:22:00', 'S', 1, '2016-07-08 13:22:45', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.626'),
(601, 1, 1234567, 1, '2016-07-08 13:59:00', 'E', 1, '2016-07-08 13:59:18', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.627'),
(602, 1, 1234567, 1, '2016-07-06 08:44:00', 'E', 1, '2016-07-06 08:44:32', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.628'),
(603, 1, 1234567, 1, '2016-07-08 18:23:00', 'S', 1, '2016-07-08 18:23:25', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.629');

-- --------------------------------------------------------

--
-- Estrutura da tabela `repositorio`
--

CREATE TABLE IF NOT EXISTS `repositorio` (
  `cod_repositorio` int(6) NOT NULL AUTO_INCREMENT,
  `nome_arquivo` varchar(255) NOT NULL,
  `chave_repositorio` varchar(12) NOT NULL,
  `chave_autenticacao` varchar(50) DEFAULT NULL,
  `data_criacao` datetime NOT NULL,
  `data_expiracao` datetime DEFAULT NULL,
  PRIMARY KEY (`cod_repositorio`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `restricao_relogio`
--

CREATE TABLE IF NOT EXISTS `restricao_relogio` (
  `nr_restricao` int(12) NOT NULL AUTO_INCREMENT,
  `id_orgao` int(5) DEFAULT NULL,
  `escopo` char(1) DEFAULT NULL,
  `id_pessoa` int(6) DEFAULT NULL,
  `mascara_ip_v4` varchar(18) DEFAULT NULL,
  `mascara_ip_v6` varchar(45) DEFAULT NULL,
  `data_atualizacao` datetime NOT NULL,
  `id_pessoa_atualizacao` int(6) NOT NULL,
  `ip_atualizacao` varchar(39) NOT NULL,
  `matricula` int(8) DEFAULT NULL,
  `nr_vinculo` int(1) DEFAULT NULL,
  PRIMARY KEY (`nr_restricao`),
  KEY `id_orgao` (`id_orgao`),
  KEY `id_pessoa` (`id_pessoa`),
  KEY `matricula` (`matricula`,`nr_vinculo`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Extraindo dados da tabela `restricao_relogio`
--

INSERT INTO `restricao_relogio` (`nr_restricao`, `id_orgao`, `escopo`, `id_pessoa`, `mascara_ip_v4`, `mascara_ip_v6`, `data_atualizacao`, `id_pessoa_atualizacao`, `ip_atualizacao`, `matricula`, `nr_vinculo`) VALUES
(1, 3, NULL, NULL, '143.54.235.130/32', NULL, '2016-08-16 00:00:00', 1, '143.54.235.130', NULL, NULL);

-- --------------------------------------------------------

--
-- Estrutura da tabela `tipo_frequencia`
--

CREATE TABLE IF NOT EXISTS `tipo_frequencia` (
  `cod_frequencia` int(3) NOT NULL AUTO_INCREMENT,
  `nome_frequencia` varchar(255) NOT NULL,
  PRIMARY KEY (`cod_frequencia`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Extraindo dados da tabela `tipo_frequencia`
--

INSERT INTO `tipo_frequencia` (`cod_frequencia`, `nome_frequencia`) VALUES
(1, 'Férias');

-- --------------------------------------------------------

--
-- Stand-in structure for view `v_ponto_e_ajuste`
--
CREATE TABLE IF NOT EXISTS `v_ponto_e_ajuste` (
`nr_seq` int(12)
,`id_pessoa` int(11)
,`matricula` int(11)
,`nr_vinculo` int(11)
,`data_hora_ponto` datetime
,`entrada_saida` char(1)
,`id_pessoa_registro` int(11)
,`data_hora_registro` datetime
,`ip_registro` varchar(39)
,`justificativa` text
,`nr_justificativa` int(11)
,`texto_justificativa` varchar(255)
,`id_pessoa_certificacao` int(11)
,`data_hora_certificacao` datetime
,`indicador_certificado` char(1)
,`tipo` varchar(1)
);
-- --------------------------------------------------------

--
-- Structure for view `v_ponto_e_ajuste`
--
DROP TABLE IF EXISTS `v_ponto_e_ajuste`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_ponto_e_ajuste` AS select `p`.`nr_ponto` AS `nr_seq`,`p`.`id_pessoa` AS `id_pessoa`,`p`.`matricula` AS `matricula`,`p`.`nr_vinculo` AS `nr_vinculo`,`p`.`data_hora_ponto` AS `data_hora_ponto`,`p`.`entrada_saida` AS `entrada_saida`,`p`.`id_pessoa_registro` AS `id_pessoa_registro`,`p`.`data_hora_registro` AS `data_hora_registro`,`p`.`ip_registro` AS `ip_registro`,NULL AS `justificativa`,NULL AS `nr_justificativa`,NULL AS `texto_justificativa`,NULL AS `id_pessoa_certificacao`,NULL AS `data_hora_certificacao`,NULL AS `indicador_certificado`,'R' AS `tipo` from `ponto` `p` where (not(exists(select 1 from `ajuste` `a` where ((`a`.`nr_ponto` = `p`.`nr_ponto`) and (`a`.`indicador_certificado` = 'S'))))) union select `a`.`nr_ajuste` AS `nr_seq`,`a`.`id_pessoa` AS `id_pessoa`,`a`.`matricula` AS `matricula`,`a`.`nr_vinculo` AS `nr_vinculo`,`a`.`data_hora_ponto` AS `data_hora_ponto`,`a`.`entrada_saida` AS `entrada_saida`,`a`.`id_pessoa_registro` AS `id_pessoa_registro`,`a`.`data_hora_registro` AS `data_hora_registro`,`a`.`ip_registro` AS `ip_registro`,`a`.`justificativa` AS `justificativa`,`a`.`nr_justificativa` AS `nr_justificativa`,`j`.`texto_justificativa` AS `texto_justificativa`,`a`.`id_pessoa_certificacao` AS `id_pessoa_certificacao`,`a`.`data_hora_certificacao` AS `data_hora_certificacao`,`a`.`indicador_certificado` AS `indicador_certificado`,'A' AS `tipo` from (`ajuste` `a` left join `justificativa_ajuste` `j` on((`a`.`nr_justificativa` = `j`.`nr_justificativa`))) where (coalesce(`a`.`indicador_excluido`,'N') = 'N');

--
-- Constraints for dumped tables
--

--
-- Limitadores para a tabela `abono`
--
ALTER TABLE `abono`
  ADD CONSTRAINT `abono_ibfk_1` FOREIGN KEY (`id_pessoa`) REFERENCES `pessoa` (`id_pessoa`),
  ADD CONSTRAINT `abono_ibfk_2` FOREIGN KEY (`matricula`) REFERENCES `dado_funcional` (`matricula`),
  ADD CONSTRAINT `abono_ibfk_3` FOREIGN KEY (`id_pessoa_certificacao`) REFERENCES `pessoa` (`id_pessoa`),
  ADD CONSTRAINT `abono_ibfk_4` FOREIGN KEY (`id_pessoa_registro`) REFERENCES `pessoa` (`id_pessoa`),
  ADD CONSTRAINT `abono_ibfk_5` FOREIGN KEY (`nr_justificativa`) REFERENCES `justificativa_ajuste` (`nr_justificativa`);

--
-- Limitadores para a tabela `ajuste`
--
ALTER TABLE `ajuste`
  ADD CONSTRAINT `ajuste_ibfk_1` FOREIGN KEY (`id_pessoa`) REFERENCES `pessoa` (`id_pessoa`),
  ADD CONSTRAINT `ajuste_ibfk_2` FOREIGN KEY (`matricula`) REFERENCES `dado_funcional` (`matricula`),
  ADD CONSTRAINT `ajuste_ibfk_3` FOREIGN KEY (`id_pessoa_registro`) REFERENCES `pessoa` (`id_pessoa`),
  ADD CONSTRAINT `ajuste_ibfk_4` FOREIGN KEY (`id_pessoa_certificacao`) REFERENCES `pessoa` (`id_pessoa`),
  ADD CONSTRAINT `ajuste_ibfk_5` FOREIGN KEY (`nr_ponto`) REFERENCES `ponto` (`nr_ponto`),
  ADD CONSTRAINT `ajuste_ibfk_6` FOREIGN KEY (`nr_justificativa`) REFERENCES `justificativa_ajuste` (`nr_justificativa`);

--
-- Limitadores para a tabela `ch_mes_servidor`
--
ALTER TABLE `ch_mes_servidor`
  ADD CONSTRAINT `ch_mes_servidor_ibfk_1` FOREIGN KEY (`id_pessoa`) REFERENCES `pessoa` (`id_pessoa`),
  ADD CONSTRAINT `ch_mes_servidor_ibfk_2` FOREIGN KEY (`matricula`) REFERENCES `dado_funcional` (`matricula`);

--
-- Limitadores para a tabela `dado_funcional`
--
ALTER TABLE `dado_funcional`
  ADD CONSTRAINT `dado_funcional_ibfk_1` FOREIGN KEY (`id_pessoa`) REFERENCES `pessoa` (`id_pessoa`),
  ADD CONSTRAINT `dado_funcional_ibfk_2` FOREIGN KEY (`id_grupo`) REFERENCES `grupo_emprego` (`id_grupo`),
  ADD CONSTRAINT `dado_funcional_ibfk_3` FOREIGN KEY (`id_categoria`) REFERENCES `categoria` (`id_categoria`),
  ADD CONSTRAINT `dado_funcional_ibfk_4` FOREIGN KEY (`orgao_lotacao`) REFERENCES `orgao` (`id_orgao`),
  ADD CONSTRAINT `dado_funcional_ibfk_5` FOREIGN KEY (`orgao_exercicio`) REFERENCES `orgao` (`id_orgao`);

--
-- Limitadores para a tabela `definicoes_orgao`
--
ALTER TABLE `definicoes_orgao`
  ADD CONSTRAINT `definicoes_orgao_ibfk_1` FOREIGN KEY (`id_orgao`) REFERENCES `orgao` (`id_orgao`);

--
-- Limitadores para a tabela `frequencia`
--
ALTER TABLE `frequencia`
  ADD CONSTRAINT `frequencia_ibfk_1` FOREIGN KEY (`matricula`) REFERENCES `dado_funcional` (`matricula`),
  ADD CONSTRAINT `frequencia_ibfk_2` FOREIGN KEY (`cod_frequencia`) REFERENCES `tipo_frequencia` (`cod_frequencia`);

--
-- Limitadores para a tabela `log_erro_acesso_registro`
--
ALTER TABLE `log_erro_acesso_registro`
  ADD CONSTRAINT `log_erro_acesso_registro_ibfk_1` FOREIGN KEY (`id_pessoa`) REFERENCES `pessoa` (`id_pessoa`),
  ADD CONSTRAINT `log_erro_acesso_registro_ibfk_2` FOREIGN KEY (`matricula`) REFERENCES `dado_funcional` (`matricula`);

--
-- Limitadores para a tabela `orgao`
--
ALTER TABLE `orgao`
  ADD CONSTRAINT `orgao_ibfk_1` FOREIGN KEY (`matricula_dirigente`) REFERENCES `dado_funcional` (`matricula`),
  ADD CONSTRAINT `orgao_ibfk_2` FOREIGN KEY (`matricula_substituto`) REFERENCES `dado_funcional` (`matricula`),
  ADD CONSTRAINT `orgao_ibfk_3` FOREIGN KEY (`id_orgao_superior`) REFERENCES `orgao` (`id_orgao`);

--
-- Limitadores para a tabela `permissao`
--
ALTER TABLE `permissao`
  ADD CONSTRAINT `permissao_ibfk_1` FOREIGN KEY (`id_pessoa`) REFERENCES `pessoa` (`id_pessoa`),
  ADD CONSTRAINT `permissao_ibfk_2` FOREIGN KEY (`id_orgao`) REFERENCES `orgao` (`id_orgao`);

--
-- Limitadores para a tabela `ponto`
--
ALTER TABLE `ponto`
  ADD CONSTRAINT `ponto_ibfk_1` FOREIGN KEY (`id_pessoa`) REFERENCES `pessoa` (`id_pessoa`),
  ADD CONSTRAINT `ponto_ibfk_2` FOREIGN KEY (`matricula`) REFERENCES `dado_funcional` (`matricula`),
  ADD CONSTRAINT `ponto_ibfk_3` FOREIGN KEY (`id_pessoa_registro`) REFERENCES `pessoa` (`id_pessoa`);

--
-- Limitadores para a tabela `restricao_relogio`
--
ALTER TABLE `restricao_relogio`
  ADD CONSTRAINT `restricao_relogio_ibfk_1` FOREIGN KEY (`id_orgao`) REFERENCES `orgao` (`id_orgao`),
  ADD CONSTRAINT `restricao_relogio_ibfk_2` FOREIGN KEY (`id_pessoa`) REFERENCES `pessoa` (`id_pessoa`),
  ADD CONSTRAINT `restricao_relogio_ibfk_3` FOREIGN KEY (`matricula`) REFERENCES `dado_funcional` (`matricula`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
