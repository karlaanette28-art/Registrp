-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 29-10-2026 a las 23:56:04
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `registros`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `registro_personal`
--

CREATE TABLE `registro_personal` (
  `id_Registro_personal` int(10) NOT NULL,
  `Apellido_Paterno` varchar(15) DEFAULT NULL,
  `Apellido_Materno` varchar(15) DEFAULT NULL,
  `Nombre` varchar(15) DEFAULT NULL,
  `RFC` varchar(15) DEFAULT NULL,
  `CURP` varchar(20) DEFAULT NULL,
  `Fecha_de_Nacimiento` date DEFAULT NULL,
  `Escolaridad` varchar(15) DEFAULT NULL,
  `Estado_Civil` varchar(15) DEFAULT NULL,
  `Colonia` varchar(15) DEFAULT NULL,
  `Calle` varchar(20) DEFAULT NULL,
  `Municipio` varchar(15) DEFAULT NULL,
  `Codigo_Postal` varchar(10) DEFAULT NULL,
  `Telefono` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `registro_personal`
--

INSERT INTO `registro_personal` (`id_Registro_personal`, `Apellido_Paterno`, `Apellido_Materno`, `Nombre`, `RFC`, `CURP`, `Fecha_de_Nacimiento`, `Escolaridad`, `Estado_Civil`, `Colonia`, `Calle`, `Municipio`, `Codigo_Postal`, `Telefono`) VALUES
(6, 'EERGER', 'RTERGRH', 'RGTRHGTR', 'TH65Y65EYHE', 'T45T84GT84G', '2006-10-10', 'universidad', 'soltera', 'juarez', 'Av.pavon', 'toluca', '79879', 1245673182),
(44, 'Palacios', 'DE LA CRUZ', 'SANTIAGO MATEO', 'MAND65R72YIQ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(45, 'udvfudsauhf', 'ijgbfajhbgbg', 'fjnfjgbfdbgkb', 'kjnbfgjbfbgij', 'jhgehbgheb', '1890-05-11', 'Preparatoria', 'CASADO/A', 'SANTA MARIA', 'TERESA', 'TOLUCA', '76876', 2147483647),
(46, 'vhdvvhg', 'vvfgvfvf', 'uhvfvvf', 'hjhbrkgbh', 'jrgbhgbbguhr', '3763-05-06', 'Universidad', 'UNION LIBRE', 'ggbbgbfbgur', 'fhvghhvghvghvg', 'uhuhbhgijgb', '34345', 2147483647);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `registro_personal`
--
ALTER TABLE `registro_personal`
  ADD PRIMARY KEY (`id_Registro_personal`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `registro_personal`
--
ALTER TABLE `registro_personal`
  MODIFY `id_Registro_personal` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
