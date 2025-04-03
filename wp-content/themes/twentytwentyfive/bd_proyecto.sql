-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 03-04-2025 a las 16:58:09
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
-- Base de datos: `bd_proyecto`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `alumnos`
--

CREATE TABLE `alumnos` (
  `id` int(11) NOT NULL,
  `nombre` varchar(150) NOT NULL,
  `apellidos` varchar(150) NOT NULL,
  `telefono` varchar(15) NOT NULL,
  `email` varchar(150) NOT NULL,
  `ASIR` tinyint(1) NOT NULL,
  `DAW` tinyint(1) NOT NULL,
  `DAM` tinyint(1) NOT NULL,
  `SMR` tinyint(1) NOT NULL,
  `VIDEOJUEGOS` tinyint(1) NOT NULL,
  `OTROS` varchar(150) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `alumnos`
--

INSERT INTO `alumnos` (`id`, `nombre`, `apellidos`, `telefono`, `email`, `ASIR`, `DAW`, `DAM`, `SMR`, `VIDEOJUEGOS`, `OTROS`) VALUES
(1, 'sadasd', 'asdasd', '6352132569', 'nuevo@gmail.com', 1, 1, 1, 0, 0, ''),
(32, 'Sergio', 'Fernández Siles', '671712781', 'spensob0410@g.educaand.es', 0, 1, 0, 0, 0, ''),
(35, 'Nuevo', 'Aluno videjugos', '6845124256', 'nuevoalumno@gmail.com', 0, 0, 0, 0, 1, ''),
(37, 'jdklasdkl', 'asjdlaskdjklasd', '123123123', 'askldjasd@gmail.com', 0, 0, 0, 1, 1, NULL),
(42, 'asdasdsad', 'asdasd', 'sadasd', 'sadasdsad@gmail.com', 0, 0, 0, 0, 0, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `empresas`
--

CREATE TABLE `empresas` (
  `id` int(11) NOT NULL,
  `nombre` varchar(150) NOT NULL,
  `direccion` varchar(200) NOT NULL,
  `telefono` varchar(15) NOT NULL,
  `email` varchar(150) NOT NULL,
  `telefono_contacto` varchar(15) NOT NULL,
  `codigo_empresa` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `empresas`
--

INSERT INTO `empresas` (`id`, `nombre`, `direccion`, `telefono`, `email`, `telefono_contacto`, `codigo_empresa`) VALUES
(4, 'Pescaderia S.L', 'c/ jardineria nº11', '6653212312', 'pruebapescaderia@gmail.com', '6989631232', 'A234412321'),
(5, 'Empresa A', 'Calle Falsa 123, Madrid', '912345678', 'contacto@empresaa.com', '678901234', 'EA1234'),
(6, 'Empresa B', 'Avenida Libertad 456, Barcelona', '933456789', 'info@empresab.com', '634567890', 'EB5678'),
(7, 'Empresa C', 'Plaza Mayor 789, Valencia', '961234567', 'atencion@empresac.com', '612345678', 'EC9876'),
(8, 'Empresa D', 'Calle Real 101, Sevilla', '954321987', 'contacto@empresad.com', '600123456', 'ED3456'),
(12, 'sadasd', 'asdasd', 'asdasdas', 'asdasd@gmail.com', 'sadasdad', 'sadsad'),
(13, 'Nueva Empresa', 'saldkasjkd', 'askldjaskldjas', 'klasjdklasd@gmail.com', '123123123', '123123123');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ofertas`
--

CREATE TABLE `ofertas` (
  `id` int(11) NOT NULL,
  `codigo_empresa` varchar(50) NOT NULL,
  `titulo` varchar(200) NOT NULL,
  `descripcion` text NOT NULL,
  `ASIR` tinyint(1) NOT NULL,
  `DAW` tinyint(1) NOT NULL,
  `DAM` tinyint(1) NOT NULL,
  `SMR` tinyint(1) NOT NULL,
  `VIDEOJUEGOS` tinyint(1) NOT NULL,
  `OTROS` varchar(150) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `ofertas`
--

INSERT INTO `ofertas` (`id`, `codigo_empresa`, `titulo`, `descripcion`, `ASIR`, `DAW`, `DAM`, `SMR`, `VIDEOJUEGOS`, `OTROS`) VALUES
(20, 'A234412321', 'Desarrollador Web', 'Esto es una preuba de descripcion', 1, 0, 0, 0, 0, NULL),
(24, 'A234412321', 'sadasd', 'asdasdsa', 0, 0, 0, 0, 0, NULL),
(25, 'A234412321', 'asdasd21312', 'asdsadasd12321', 0, 0, 0, 0, 0, '21321312'),
(27, 'A234412321', 'asdasd21312', 'asdsadasd12321', 1, 1, 1, 1, 0, '21321312'),
(29, 'A234412321', 'Videojuegos', 'Videojuegos', 0, 0, 0, 0, 1, 'Cocina'),
(30, 'A234412321', 'Videojuegos', 'Videojuegos', 1, 1, 1, 1, 1, 'Cocina'),
(32, 'A234412321', 'asdasd', 'sadasd', 0, 1, 0, 0, 0, ''),
(35, 'A234412321', 'Nueva oferta', 'sadasd', 1, 0, 0, 0, 1, '');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `profesores`
--

CREATE TABLE `profesores` (
  `id` int(10) NOT NULL,
  `email` varchar(200) NOT NULL,
  `password` varchar(200) NOT NULL,
  `nombre_usuario` varchar(150) NOT NULL,
  `nombre` varchar(150) NOT NULL,
  `apellidos` varchar(150) NOT NULL,
  `rol` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `profesores`
--

INSERT INTO `profesores` (`id`, `email`, `password`, `nombre_usuario`, `nombre`, `apellidos`, `rol`) VALUES
(11, 'asdjasldasjds@gmail.com', '$2y$10$77VlVbuomojwUZkfW7DnLOYcHxQ3qc62gu1M2r4DaKVJWZZqI66uW', 'david', '12312312312321', 'asdasd', 'admin'),
(14, 'spensob0410@g.educaand.es', '$2y$10$/BwMWEgnR4tFiFLMj/Jy6.2vbYHEjJoYdkeuDC/mR/9afuORxN9oG', 'sergio', 'Sergio', 'Peña Sobrado', 'admin'),
(15, 'jesushernandez@gmail.com', '$2y$10$q56mUJjCQQs93JS7cS58MuODZxrJiLGUCeKKPxu6QuznpU3Csby1a', 'alca20', 'Jesús', 'López Hernández', 'admin'),
(16, 'juan.perez@example.com', '', 'juanperez', 'Juan', 'Pérez', 'estandar'),
(18, 'jose.martin@example.com', '', 'josemartin', 'José', 'Martín', 'admin'),
(19, 'luis.rodriguez@example.com', '', 'luisrodriguez', 'Luis', 'Rodríguez', 'estandar'),
(20, 'carlos.sanchez@example.com', '', 'carlossanchez', 'Carlos', 'Sánchez', 'estandar'),
(21, 'antonio.ruiz@example.com', '', 'antonioruiz', 'Antonio', 'Ruiz', 'admin'),
(25, 'rodrigo.perez@example.com', '', 'rodrigoperez', 'Rodrigo', 'Pérez', 'admin'),
(28, 'sofia.herrera@example.com', '', 'sofiaherrera', 'Sofía', 'Herrera', 'estandar'),
(29, 'pedro.alvarez@example.com', '', 'pedroalvarez', 'Pedro', 'Álvarez', 'admin'),
(35, 'asdasd2@gmail.com', '$2y$10$24NkJM49peiLgvHUngGYjO7fb.THkZ7pF4SGInSH3jcrpD.b.W7le', 'profesornuevo12', 'Nuevop', 'Profesor 2', 'estandar');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `alumnos`
--
ALTER TABLE `alumnos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `empresas`
--
ALTER TABLE `empresas`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_codigo_empresa` (`codigo_empresa`);

--
-- Indices de la tabla `ofertas`
--
ALTER TABLE `ofertas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_codigo_empresa` (`codigo_empresa`);

--
-- Indices de la tabla `profesores`
--
ALTER TABLE `profesores`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_nombre_usuario` (`nombre_usuario`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `alumnos`
--
ALTER TABLE `alumnos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT de la tabla `empresas`
--
ALTER TABLE `empresas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de la tabla `ofertas`
--
ALTER TABLE `ofertas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT de la tabla `profesores`
--
ALTER TABLE `profesores`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `ofertas`
--
ALTER TABLE `ofertas`
  ADD CONSTRAINT `fk_codigo_empresa` FOREIGN KEY (`codigo_empresa`) REFERENCES `empresas` (`codigo_empresa`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
