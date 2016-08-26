-- phpMyAdmin SQL Dump
-- version 4.0.4.2
-- http://www.phpmyadmin.net
--
-- Máquina: localhost
-- Data de Criação: 18-Ago-2016 às 19:11
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

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
(1234567, 1, 1, '40', 1, 1, 2, 3, '2000-01-02 00:00:00', NULL, NULL);

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
  `hora_inicio_expediente_sab` datetime DEFAULT NULL,
  `hora_fim_expediente_sab` datetime DEFAULT NULL,
  `hora_inicio_expediente_dom` datetime DEFAULT NULL,
  `hora_fim_expediente_dom` datetime DEFAULT NULL,
  PRIMARY KEY (`id_orgao`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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
(1, 1234567, 1, 2, '2016-08-29 00:00:00', '2016-08-31 00:00:00', 1);

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
(1, 'T', 'Técnico-administrativo');

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
(1, 'UNI', 'Universidade', NULL, NULL, NULL, NULL),
(2, 'UAC', 'Unidade Acadêmica', NULL, NULL, NULL, 1),
(3, 'DEPA', 'Departamento de Afazeres', NULL, NULL, NULL, 2);

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
(1, 'Fulano de Tal', 'fulano@email.com', 'gif');

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Extraindo dados da tabela `ponto`
--

INSERT INTO `ponto` (`nr_ponto`, `id_pessoa`, `matricula`, `nr_vinculo`, `data_hora_ponto`, `entrada_saida`, `id_pessoa_registro`, `data_hora_registro`, `ip_registro`, `ambiente_registro`) VALUES
(1, 1, 1234567, 1, '2016-08-16 16:55:49', 'E', 1, '2016-08-16 16:56:19', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.36'),
(2, 1, 1234567, 1, '2016-08-16 17:26:10', 'S', 1, '2016-08-16 17:26:40', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.36'),
(3, 1, 1234567, 1, '2016-08-16 17:26:24', 'E', 1, '2016-08-16 17:26:54', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.36');

-- --------------------------------------------------------

--
-- Estrutura da tabela `repositorio`
--

CREATE TABLE IF NOT EXISTS `repositorio` (
  `cod_repositorio` int(6) NOT NULL AUTO_INCREMENT,
  `nome_arquivo` varchar(255) NOT NULL,
  `chave_repositorio` varchar(12) NOT NULL,
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
  ADD CONSTRAINT `permissao_ibfk_2` FOREIGN KEY (`id_orgao`) REFERENCES `orgao` (`id_orgao`),
  ADD CONSTRAINT `permissao_ibfk_1` FOREIGN KEY (`id_pessoa`) REFERENCES `pessoa` (`id_pessoa`);

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
