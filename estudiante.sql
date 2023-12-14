-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1:3306
-- Tiempo de generación: 14-12-2023 a las 19:18:18
-- Versión del servidor: 8.0.31
-- Versión de PHP: 8.0.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `expedientes`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estudiante`
--

DROP TABLE IF EXISTS `estudiante`;
CREATE TABLE IF NOT EXISTS `estudiante` (
  `id` int NOT NULL AUTO_INCREMENT,
  `numero_expediente` varchar(15) DEFAULT NULL,
  `nif` varchar(9) NOT NULL,
  `nombre` varchar(10) NOT NULL,
  `apellido1` varchar(10) NOT NULL,
  `apellido2` varchar(10) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `telefono_movil` varchar(15) NOT NULL,
  `imagen` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `numero_expediente` (`numero_expediente`)
) ;

--
-- Volcado de datos para la tabla `estudiante`
--

INSERT INTO `estudiante` (`id`, `numero_expediente`, `nif`, `nombre`, `apellido1`, `apellido2`, `email`, `telefono_movil`, `imagen`) VALUES
(1, '2022-0001/H', '12345678A', 'Juan', 'Gomez', 'Perez', 'juan@example.com', '123456789', ''),
(2, '2022-0002/M', '98765432B', 'Maria', 'Lopez', 'Garcias', 'maria@example.com', '987654321', 'images/Screenshot_2.png'),
(3, '2022-0003/H', '56789012C', 'Carlos', 'Rodriguez', 'Perez', 'carlos@example.com', '345678901', 'images/Concesionario.png'),
(4, '2022-0004/M', '34567890D', 'Laura', 'Fernandez', 'Gomez', 'laura@example.com', '234567890', ''),
(6, '2022-0008/H', '49281331J', 'Jose', 'Cruz', 'Regalado', 'prueba@prueba.com', '618147150', 'images/'),
(7, '2022-0022/H', '49281331J', 'Jose', 'Dominguez', 'Perez', 'prueba@prueba.com', '618147155', 'images/');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
