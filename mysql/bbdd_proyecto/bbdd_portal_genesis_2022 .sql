-- MySQL dump 10.13  Distrib 5.7.33, for Linux (x86_64)
--
-- Host: localhost    Database: portal
-- ------------------------------------------------------
-- Server version	5.7.33-0ubuntu0.16.04.1



SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';
--
-- Table structure for table `Errores`
--
DROP DATABASE IF  EXISTS `bbdd_portal_genesis_2022`;

CREATE SCHEMA IF NOT EXISTS `bbdd_portal_genesis_2022` DEFAULT CHARACTER SET utf8mb4 ;
USE `bbdd_portal_genesis_2022` ;


USE `bbdd_portal_genesis_2022` ;

-- -----------------------------------------------------
-- Table `bbdd_portal_genesis_2022`.`administradores`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `bbdd_portal_genesis_2022`.`administradores` (
  `idAdmin` INT(11) NOT NULL,
  PRIMARY KEY (`idAdmin`))
ENGINE = InnoDB;

DROP TABLE IF EXISTS `Errores`;

CREATE TABLE `Errores` (
  `idErrores` int NOT NULL AUTO_INCREMENT,
  `motivo` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `codigo` int NOT NULL,
  `usuario` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `fechaError` datetime NOT NULL,
  `mensaje` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `mensajePHP` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `codigoPHP` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `fichero` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `linea` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `trace` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci,
  `DatosIntroducidos` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci,
  PRIMARY KEY (`idErrores`))
 ENGINE=InnoDB 
 AUTO_INCREMENT=2 
 DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_unicode_520_ci;



--
-- Table structure for table `busquedas_pbs_buscadas`
--

DROP TABLE IF EXISTS `busquedas_pbs_buscadas`;

CREATE TABLE `busquedas_pbs_buscadas` (
  `idPbsBuscada` int NOT NULL AUTO_INCREMENT,
  `idPostQueridas` int NOT NULL,
  `palabrasBuscadas` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  PRIMARY KEY (`idPbsBuscada`),
  KEY `fk_busquedas_pbs_buscadas_post1_idx` (`idPostQueridas`),
  FULLTEXT KEY `palabrasBuscadas` (`palabrasBuscadas`),
  CONSTRAINT `fk_busquedas_pbs_buscadas_post1` FOREIGN KEY (`idPostQueridas`) REFERENCES `post` (`idPost`) 
ON DELETE CASCADE ON UPDATE CASCADE)
 ENGINE=InnoDB 
AUTO_INCREMENT=53 
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_unicode_520_ci;


--
-- Table structure for table `busquedas_pbs_ofrecidas`
--

DROP TABLE IF EXISTS `busquedas_pbs_ofrecidas`;

CREATE TABLE `busquedas_pbs_ofrecidas` (
  `idPbsOfrecida` int NOT NULL AUTO_INCREMENT,
  `idPostOfrecidas` int NOT NULL,
  `palabrasOfrecidas` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  PRIMARY KEY (`idPbsOfrecida`),
  KEY `fk_busquedas_pbs_ofrecidas_post1_idx` (`idPostOfrecidas`),
  FULLTEXT KEY `palabrasOfrecidas` (`palabrasOfrecidas`),
  CONSTRAINT `fk_busquedas_pbs_ofrecidas_post1` FOREIGN KEY (`idPostOfrecidas`) REFERENCES `post` (`idPost`) 
ON DELETE CASCADE ON UPDATE CASCADE)
 ENGINE=InnoDB AUTO_INCREMENT=53 
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_unicode_520_ci;


--
-- Table structure for table `comentarios_post`
--

DROP TABLE IF EXISTS `comentarios_post`;

CREATE TABLE `comentarios_post` (
  `idComentariosPosts` int NOT NULL AUTO_INCREMENT,
  `usuarioIdUsuario` int NOT NULL,
  `postIdPost` int NOT NULL,
  `nombreComenta` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `imgUsuarioComentario` varchar(75) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `ciudadComentario` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `tituloComentario` varchar(75) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `comentariosPost` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `fechaComentario` date NOT NULL,
  PRIMARY KEY (`idComentariosPosts`),
  KEY `fk_usuario_has_post_post2_idx` (`postIdPost`),
  KEY `fk_usuario_has_post_usuario2_idx` (`usuarioIdUsuario`),
  CONSTRAINT `fk_usuario_has_post_post2`
 FOREIGN KEY (`postIdPost`) REFERENCES 
`post` (`idPost`)ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `fk_usuario_has_post_usuario` 
FOREIGN KEY (`usuarioIdUsuario`) REFERENCES `usuario` (`idUsuario`) 
ON DELETE CASCADE ON UPDATE NO ACTION) 
ENGINE=InnoDB 
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_unicode_520_ci;


--
-- Table structure for table `datos_usuario`
--

DROP TABLE IF EXISTS `datos_usuario`;

CREATE TABLE `datos_usuario` (
  `idDatosUsuario` int NOT NULL,
  `nombre` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `apellido_1` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `apellido_2` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `telefono` char(9) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `genero` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  PRIMARY KEY (`idDatosUsuario`),
  KEY `fk_datos_usuario_usuario1_idx` (`idDatosUsuario`),
  CONSTRAINT `fk_datos_usuario_usuario1` FOREIGN KEY (`idDatosUsuario`) 
REFERENCES `usuario` (`idUsuario`) ON DELETE CASCADE ON UPDATE CASCADE) 
ENGINE=InnoDB 
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_unicode_520_ci;


--
-- Table structure for table `direccion`
--

DROP TABLE IF EXISTS `direccion`;

CREATE TABLE `direccion` (
  `idDireccion` int NOT NULL,
  `calle` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `numeroPortal` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `ptr` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `codigoPostal` char(5) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `ciudad` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `provincia` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `pais` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  PRIMARY KEY (`idDireccion`),
  KEY `fk_direccion_usuario1_idx` (`idDireccion`),
  CONSTRAINT `fk_direccion_usuario1` FOREIGN KEY (`idDireccion`) REFERENCES `usuario` (`idUsuario`) ON DELETE CASCADE ON UPDATE CASCADE) ENGINE=InnoDB 
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_unicode_520_ci;



--
-- Table structure for table `genero`
--

DROP TABLE IF EXISTS `genero`;

CREATE TABLE `genero` (
  `idGenero` int NOT NULL AUTO_INCREMENT,
  `genero` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  PRIMARY KEY (`idGenero`))
 ENGINE=InnoDB AUTO_INCREMENT=7 
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_unicode_520_ci;



--
-- Table structure for table `imagenes`
--

DROP TABLE IF EXISTS `imagenes`;

CREATE TABLE `imagenes` (
  `idImagen` int NOT NULL AUTO_INCREMENT,
  `postIdPost` int NOT NULL,
  `directorio` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `texto` varchar(90) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  PRIMARY KEY (`idImagen`),
  KEY `fk_imagenes_post1_idx` (`postIdPost`),
  CONSTRAINT `fk_imagenes_post1`
 FOREIGN KEY (`postIdPost`) REFERENCES `post` (`idPost`) ON DELETE CASCADE ON UPDATE CASCADE) 
ENGINE=InnoDB AUTO_INCREMENT=42 
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_unicode_520_ci;



--
-- Table structure for table `palabras_email`
--

DROP TABLE IF EXISTS `palabras_email`;

CREATE TABLE `palabras_email` (
  `idPalabrasEmail` int NOT NULL AUTO_INCREMENT,
  `usuarioIdUsuario` int NOT NULL,
  `email` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `palabrasDetectar` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  PRIMARY KEY (`idPalabrasEmail`,`usuarioIdUsuario`),
  KEY `fk_palabras_email_usuario1_idx` (`usuarioIdUsuario`),
  FULLTEXT KEY `palabras_decta` (`palabrasDetectar`),
  CONSTRAINT `fk_palabras_email_usuario1` FOREIGN KEY (`usuarioIdUsuario`) REFERENCES `usuario` (`idUsuario`)
 ON DELETE CASCADE ON UPDATE CASCADE) 
ENGINE=InnoDB AUTO_INCREMENT=7 
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_unicode_520_ci;



--
-- Table structure for table `post`
--

DROP TABLE IF EXISTS `post`;

CREATE TABLE `post` (
  `idPost` int NOT NULL AUTO_INCREMENT,
  `idUsuarioPost` int NOT NULL,
  `seccionesIdsecciones` int NOT NULL,
  `tiempoCambioIdTiempoCambio` int(11) NOT NULL,
  `titulo` varchar(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `comentario` varchar(350) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `precio` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `fechaPost` date NOT NULL,
  PRIMARY KEY (`idPost`),
  KEY `fk_post_secciones1_idx` (`seccionesIdsecciones`),
  KEY `fk_post_tiempo_cambio1_idx` (`tiempoCambioIdTiempoCambio`),
  KEY `fk_post_usuario1_idx` (`idUsuarioPost`),
  CONSTRAINT `fk_post_secciones1` FOREIGN KEY (`seccionesIdsecciones`) REFERENCES `secciones` (`idSecciones`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_post_tiempo_cambio1` FOREIGN KEY (`tiempoCambioIdTiempoCambio`) REFERENCES `tiempo_cambio` (`idTiempoCambio`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_post_usuario1` FOREIGN KEY (`idUsuarioPost`) REFERENCES `usuario` (`idUsuario`) ON DELETE CASCADE ON UPDATE CASCADE) ENGINE=InnoDB AUTO_INCREMENT=14 
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_unicode_520_ci;



--
-- Table structure for table `post_denunciados`
--

DROP TABLE IF EXISTS `post_denunciados`;

CREATE TABLE `post_denunciados` (
  `usuarioIdUsuario` int NOT NULL,
  `postIdPost` int NOT NULL,
  `fechaDenunciaPost` date NOT NULL,
  `motivoDenuncia` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
  PRIMARY KEY (`usuarioIdUsuario`,`postIdPost`),
  KEY `fk_usuario_has_post_post1_idx` (`postIdPost`),
  KEY `fk_usuario_has_post_usuario1_idx` (`usuarioIdUsuario`),
  CONSTRAINT `fk_usuario_has_post_post1` FOREIGN KEY (`postIdPost`) REFERENCES `post` (`idPost`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_usuario_has_post_usuario1` FOREIGN KEY (`usuarioIdUsuario`) REFERENCES `usuario` (`idUsuario`) ON DELETE CASCADE ON UPDATE CASCADE) 
ENGINE=InnoDB 
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_unicode_520_ci;



--
-- Table structure for table `provincias`
--

DROP TABLE IF EXISTS `provincias`;

CREATE TABLE `provincias` (
  `idProvincias` int NOT NULL AUTO_INCREMENT,
  `nombreProvincia` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  PRIMARY KEY (`idProvincias`)
) ENGINE=InnoDB AUTO_INCREMENT=54 
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_unicode_520_ci;



--
-- Table structure for table `secciones`
--

DROP TABLE IF EXISTS `secciones`;

CREATE TABLE `secciones` (
  `idSecciones` int NOT NULL AUTO_INCREMENT,
  `nombreSeccion` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
  PRIMARY KEY (`idSecciones`)) 
ENGINE=InnoDB AUTO_INCREMENT=17 
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_unicode_520_ci;



--
-- Table structure for table `subsecciones`
--

DROP TABLE IF EXISTS `subsecciones`;

CREATE TABLE `subsecciones` (
  `idSubsecciones` int NOT NULL AUTO_INCREMENT,
  `seccionesIdSecciones` int NOT NULL,
  `nombreSubseccion` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
  PRIMARY KEY (`idSubsecciones`,`seccionesIdSecciones`),
  KEY `fk_subsecciones_secciones1_idx` (`seccionesIdSecciones`),
  CONSTRAINT `fk_subsecciones_secciones1` 
FOREIGN KEY (`seccionesIdSecciones`) REFERENCES `secciones` (`idSecciones`) ON DELETE CASCADE ON UPDATE CASCADE)
 ENGINE=InnoDB 
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_unicode_520_ci;



--
-- Table structure for table `tiempo_cambio`
--

DROP TABLE IF EXISTS `tiempo_cambio`;

CREATE TABLE `tiempo_cambio` (
  `idTiempoCambio` int NOT NULL AUTO_INCREMENT,
  `tiempo` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
  PRIMARY KEY (`idTiempoCambio`)) 
ENGINE=InnoDB AUTO_INCREMENT=15 
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_unicode_520_ci;



--
-- Table structure for table `usarios_mensajes_por_no_activos`
--

DROP TABLE IF EXISTS `usarios_mensajes_por_no_activos`;

CREATE TABLE `usarios_mensajes_por_no_activos` (
  `idusariosMensajesPorNoActivos` int NOT NULL,
  PRIMARY KEY (`idusariosMensajesPorNoActivos`))
 ENGINE=InnoDB 
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_unicode_520_ci;


--
-- Table structure for table `usuario`
--

DROP TABLE IF EXISTS `usuario`;

CREATE TABLE `usuario` (
  `idUsuario` int NOT NULL AUTO_INCREMENT,
  `nick` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `email` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `fecha` date NOT NULL,
  `bloqueado`	TINYINT(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`idUsuario`),
  UNIQUE KEY `nick_UNIQUE` (`nick`))
 ENGINE=InnoDB AUTO_INCREMENT=33 
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_unicode_520_ci;



--
-- Table structure for table `usuarios_bloqueados_parcial`
--

DROP TABLE IF EXISTS `usuarios_bloqueados_parcial`;

CREATE TABLE `usuarios_bloqueados_parcial` (
  `usuarioIdUsuario` int NOT NULL,
  `idUsuarioBloqueado` int NOT NULL,
  PRIMARY KEY (`usuarioIdUsuario`,`idUsuarioBloqueado`),
  CONSTRAINT `fk_usuarios_bloqueados_parcial_usuario` FOREIGN KEY (`usuarioIdUsuario`) REFERENCES `usuario` (`idUsuario`) ON DELETE CASCADE ON UPDATE NO ACTION) ENGINE=InnoDB 
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_unicode_520_ci;



--
-- Table structure for table `usuarios_bloqueados_total`
--

DROP TABLE IF EXISTS `usuarios_bloqueados_total`;

CREATE TABLE `usuarios_bloqueados_total` (
  `usuarioIdUsuario` int NOT NULL,
  `idUsuarioBloqueado` int NOT NULL,
  PRIMARY KEY (`usuarioIdUsuario`,`idUsuarioBloqueado`),
  KEY `fk_usuarios_bloqueados_usuario1_idx` (`usuarioIdUsuario`),
  CONSTRAINT `fk_usuarios_bloqueados_usuario1` 
FOREIGN KEY (`usuarioIdUsuario`) REFERENCES `usuario` (`idUsuario`) ON DELETE CASCADE ON UPDATE NO ACTION) 
ENGINE=InnoDB 
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_unicode_520_ci;


--
-- Table structure for table `usuarios_expulsados`
--

DROP TABLE IF EXISTS `usuarios_expulsados`;

CREATE TABLE `usuarios_expulsados` (
  `usuarioIdUsuario` int NOT NULL,
  PRIMARY KEY (`usuarioIdUsuario`),
  CONSTRAINT `fk_usuarios_expulsados_usuario1`
 FOREIGN KEY (`usuarioIdUsuario`) REFERENCES `usuario` (`idUsuario`) ON DELETE CASCADE ON UPDATE NO ACTION) 
ENGINE=InnoDB 
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_unicode_520_ci;


-- Dump completed on 2022-01-31 18:26:32COLLATE
