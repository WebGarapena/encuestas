-- phpMyAdmin SQL Dump
-- version 3.4.5
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tiempo de generación: 05-05-2013 a las 00:52:09
-- Versión del servidor: 5.5.16
-- Versión de PHP: 5.3.8

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de datos: `prototipos_encuesta`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `encuestas`
--

CREATE TABLE IF NOT EXISTS `encuestas` (
  `id_encuesta` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Clave primaria de la tabla polls',
  `agregar` tinyint(1) NOT NULL COMMENT 'Si se pueden agregar opciones',
  `titulo` varchar(100) NOT NULL COMMENT 'Preguntas de la encuestas',
  `descripcion` varchar(500) DEFAULT NULL COMMENT 'Explicaciones de la encuesta',
  `fecha_inicio` datetime NOT NULL COMMENT 'Inicio de encuesta',
  `fecha_final` datetime DEFAULT NULL COMMENT 'Final de encuesta',
  PRIMARY KEY (`id_encuesta`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `opciones_respuestas`
--

CREATE TABLE IF NOT EXISTS `opciones_respuestas` (
  `id_opcion` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nombre_opcion` varchar(150) NOT NULL,
  `id_encuesta` int(10) unsigned NOT NULL COMMENT 'clave foranea de encuestas',
  PRIMARY KEY (`id_opcion`),
  KEY `id_encuesta` (`id_encuesta`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `respuestas_cantidad`
--

CREATE TABLE IF NOT EXISTS `respuestas_cantidad` (
  `id_respuesta_cantidad` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_opcion` int(10) unsigned NOT NULL,
  `cantidad` tinyint(3) NOT NULL,
  `fecha_respuesta_cantidad` datetime NOT NULL,
  `id_encuesta` int(10) unsigned NOT NULL,
  `id_usuario` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id_respuesta_cantidad`),
  KEY `id_opcion` (`id_opcion`),
  KEY `id_encuesta` (`id_encuesta`),
  KEY `id_usuario` (`id_usuario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `respuestas_encuestas_multiples`
--

CREATE TABLE IF NOT EXISTS `respuestas_encuestas_multiples` (
  `id_respuesta_multiple` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Identificador de respuestas multiples',
  `id_opcion` int(10) unsigned NOT NULL COMMENT 'clave foranea de opciones',
  `id_encuesta` int(10) unsigned NOT NULL COMMENT 'Clave foranea de las votaciones',
  `fecha_respuesta_multiple` datetime NOT NULL,
  `id_usuario` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id_respuesta_multiple`),
  KEY `id_opcion` (`id_opcion`),
  KEY `id_encuesta` (`id_encuesta`),
  KEY `id_usuario` (`id_usuario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `respuestas_encuestas_simples`
--

CREATE TABLE IF NOT EXISTS `respuestas_encuestas_simples` (
  `id_respuesta` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Identificador de las respuestas posibles',
  `id_opcion` int(10) unsigned NOT NULL COMMENT 'clave foranea de opciones',
  `fecha_respuesta` datetime NOT NULL,
  `id_encuesta` int(10) unsigned NOT NULL,
  `id_usuario` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id_respuesta`),
  KEY `id_opcion` (`id_opcion`),
  KEY `id_encuesta` (`id_encuesta`),
  KEY `id_usuario` (`id_usuario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE IF NOT EXISTS `usuarios` (
  `id_usuario` int(3) unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) NOT NULL,
  `contrasena` varchar(50) NOT NULL,
  PRIMARY KEY (`id_usuario`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id_usuario`, `nombre`, `contrasena`) VALUES
(1, 'jon', '123456'),
(2, 'nestor', '123456'),
(5, 'raul', '123456'),
(6, 'mikel', '123456');

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `opciones_respuestas`
--
ALTER TABLE `opciones_respuestas`
  ADD CONSTRAINT `opciones_respuestas_ibfk_1` FOREIGN KEY (`id_encuesta`) REFERENCES `encuestas` (`id_encuesta`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Filtros para la tabla `respuestas_cantidad`
--
ALTER TABLE `respuestas_cantidad`
  ADD CONSTRAINT `respuestas_cantidad_ibfk_1` FOREIGN KEY (`id_opcion`) REFERENCES `opciones_respuestas` (`id_opcion`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `respuestas_cantidad_ibfk_3` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`) ON DELETE NO ACTION ON UPDATE CASCADE;

--
-- Filtros para la tabla `respuestas_encuestas_multiples`
--
ALTER TABLE `respuestas_encuestas_multiples`
  ADD CONSTRAINT `respuestas_encuestas_multiples_ibfk_1` FOREIGN KEY (`id_opcion`) REFERENCES `opciones_respuestas` (`id_opcion`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `respuestas_encuestas_multiples_ibfk_3` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`) ON DELETE NO ACTION ON UPDATE CASCADE;

--
-- Filtros para la tabla `respuestas_encuestas_simples`
--
ALTER TABLE `respuestas_encuestas_simples`
  ADD CONSTRAINT `respuestas_encuestas_simples_ibfk_1` FOREIGN KEY (`id_opcion`) REFERENCES `opciones_respuestas` (`id_opcion`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `respuestas_encuestas_simples_ibfk_3` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`) ON DELETE NO ACTION ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
