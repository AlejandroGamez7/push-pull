-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 16-12-2025 a las 12:19:03
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `push&pull`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE `users` (
  `ID` int(11) NOT NULL,
  `USERNAME` varchar(100) NOT NULL,
  `PW` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`ID`, `USERNAME`, `PW`) VALUES
(1, 'IsaacNewton', 'pringles1234');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `vinilos`
--

CREATE TABLE `vinilos` (
  `ID` int(11) NOT NULL,
  `NOMBRE` varchar(100) NOT NULL,
  `ARTISTA` varchar(200) NOT NULL,
  `FOTO` varchar(500) NOT NULL,
  `DESCRIPCION` varchar(500) NOT NULL,
  `PRECIO` float NOT NULL,
  `AÑO` varchar(100) NOT NULL,
  `VISIBLE` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `vinilos`
--

INSERT INTO `vinilos` (`ID`, `NOMBRE`, `ARTISTA`, `FOTO`, `DESCRIPCION`, `PRECIO`, `AÑO`, `VISIBLE`) VALUES
(1, 'I\'m the problem', 'Morgan Wallen', 'MorganWallenImTheProblem.png', 'Un tío con bigote muy grandote con flow chinote tiene un estilo como un coyote, algunos le ponen mote, con la varita de Harry Potter', 49.99, '2025', 1),
(2, 'It\'s my life', 'Bon Jovi', 'BonJoviItsMyLife.png', 'Un corazón navajeado', 27.99, '2000', 1);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`ID`);

--
-- Indices de la tabla `vinilos`
--
ALTER TABLE `vinilos`
  ADD PRIMARY KEY (`ID`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `vinilos`
--
ALTER TABLE `vinilos`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
