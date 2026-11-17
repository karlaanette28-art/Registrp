-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 29-10-2026 a las 23:55:16
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
-- Estructura de tabla para la tabla `laboral`
--

CREATE TABLE `laboral` (
  `id_Laboral` int(10) NOT NULL,
  `ID_Empleado` int(11) DEFAULT NULL,
  `Genero` varchar(10) DEFAULT NULL,
  `ISSEMYM` int(10) DEFAULT NULL,
  `ID_Unidad_Org` int(11) DEFAULT NULL,
  `Unidad_Org` varchar(20) DEFAULT NULL,
  `Direccion` varchar(20) DEFAULT NULL,
  `ID_de_Plaza` int(17) DEFAULT NULL,
  `Tipo_de_Plaza` varchar(20) DEFAULT NULL,
  `Tipo_de_Servicio_Publico` varchar(25) DEFAULT NULL,
  `ID_Puesto` varchar(10) DEFAULT NULL,
  `Nombre Puesto` varchar(30) NOT NULL,
  `CCT` varchar(15) DEFAULT NULL,
  `Lugar_de_Trabajo` varchar(40) DEFAULT NULL,
  `Fecha_de_Ingreso_al_GEM` date DEFAULT NULL,
  `Fecha_de_Paga` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `laboral`
--

INSERT INTO `laboral` (`id_Laboral`, `ID_Empleado`, `Genero`, `ISSEMYM`, `ID_Unidad_Org`, `Unidad_Org`, `Direccion`, `ID_de_Plaza`, `Tipo_de_Plaza`, `Tipo_de_Servicio_Publico`, `ID_Puesto`, `Nombre Puesto`, `CCT`, `Lugar_de_Trabajo`, `Fecha_de_Ingreso_al_GEM`, `Fecha_de_Paga`) VALUES
(43, 45, 'Masculino', 2147483647, 46547658, 'DEPARTAMENTO DE PREC', 'yujfyfkuy', 1568769879, 'General', 'SUTEYM', '89098098', '', '8089089', 'DELEGACION ADMINISTRATIVA', '2026-10-02', '2026-10-30'),
(44, 46, 'Femenino', 576658789, 2147483647, 'DEPARTAMENTO DE PREC', 'guguyguf', 2147483647, 'Confianza', 'Burócratas No Sindicaliza', '5768974687', '', '768768787', 'DIR ESTUD POLITICA ING', '2026-10-01', '2026-10-30');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `laboral`
--
ALTER TABLE `laboral`
  ADD PRIMARY KEY (`id_Laboral`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `laboral`
--
ALTER TABLE `laboral`
  MODIFY `id_Laboral` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
