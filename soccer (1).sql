-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 27-11-2025 a las 01:26:58
-- Versión del servidor: 9.2.0
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `soccer`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `carrito`
--

CREATE TABLE `carrito` (
  `id_carrito` int NOT NULL,
  `id_usuario` int DEFAULT NULL,
  `id_jersey` int DEFAULT NULL,
  `id_talla` int DEFAULT NULL,
  `jugador_custom` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `cantidad` int DEFAULT NULL,
  `fecha_agregado` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `envios`
--

CREATE TABLE `envios` (
  `id_envio` int NOT NULL,
  `id_venta` int DEFAULT NULL,
  `fecha_envio` datetime DEFAULT CURRENT_TIMESTAMP,
  `transportista` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `numero_guia` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `estado` enum('pendiente','en tr?nsito','entregado') COLLATE utf8mb4_general_ci DEFAULT 'pendiente'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `envios`
--

INSERT INTO `envios` (`id_envio`, `id_venta`, `fecha_envio`, `transportista`, `numero_guia`, `estado`) VALUES
(8, 10, '2025-07-20 21:56:58', NULL, NULL, 'pendiente'),
(9, 11, '2025-07-23 15:54:17', NULL, NULL, 'pendiente'),
(10, 12, '2025-07-24 07:41:56', NULL, NULL, 'pendiente'),
(11, 13, '2025-09-04 09:17:16', NULL, NULL, 'pendiente'),
(12, 14, '2025-11-11 11:33:11', NULL, NULL, 'pendiente');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `equipos`
--

CREATE TABLE `equipos` (
  `id_equipo` int NOT NULL,
  `nombre` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `escudo_url` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `equipos`
--

INSERT INTO `equipos` (`id_equipo`, `nombre`, `escudo_url`) VALUES
(1, 'Alemania', 'static/images/crest/alemania.png'),
(2, 'Real Madrid', 'static/images/crest/realmadrid.png'),
(3, 'Barcelona', 'static/images/crest/barcelona.png'),
(4, 'Chelsea', 'static/images/crest/chelsea.png'),
(5, 'Manchester City', 'static/images/crest/manchestercity.png');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `jerseys`
--

CREATE TABLE `jerseys` (
  `id_jersey` int NOT NULL,
  `nombre` varchar(150) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `precio` decimal(8,2) DEFAULT NULL,
  `imagen_url` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `temporada` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `id_equipo` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `jerseys`
--

INSERT INTO `jerseys` (`id_jersey`, `nombre`, `precio`, `imagen_url`, `temporada`, `id_equipo`) VALUES
(1, 'Camiseta Alemania Local 2018', 799.00, 'static/images/jerseys/alemania_home.png', '2018', 1),
(2, 'Camiseta Real Madrid Local 2019/20', 749.99, 'static/images/jerseys/realmadrid_home.png', '2019/20', 2),
(3, 'Camiseta Barcelona Local 2014/15', 699.88, 'static/images/jerseys/barcelona_home.png', '2014/15', 3),
(4, 'Camiseta Chelsea Visitante 2020/21', 720.00, 'static/images/jerseys/chelsea_away.png', '2020/21', 4),
(5, 'Camiseta Manchester City Local 2021/22', 735.50, 'static/images/jerseys/mancity_home.png', '2021/22', 5);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `jersey_tallas`
--

CREATE TABLE `jersey_tallas` (
  `id_jersey` int NOT NULL,
  `id_talla` int NOT NULL,
  `stock` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `jersey_tallas`
--

INSERT INTO `jersey_tallas` (`id_jersey`, `id_talla`, `stock`) VALUES
(1, 1, 5),
(1, 2, 12),
(1, 3, 12),
(1, 4, 7),
(2, 1, 7),
(2, 2, 14),
(2, 3, 11),
(2, 4, 6),
(3, 1, 9),
(3, 2, 13),
(3, 3, 8),
(3, 4, 5),
(4, 1, 8),
(4, 2, 11),
(4, 3, 9),
(4, 4, 4),
(5, 1, 11),
(5, 2, 16),
(5, 3, 13),
(5, 4, 7);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `jugadores`
--

CREATE TABLE `jugadores` (
  `id_jugador` int NOT NULL,
  `nombre` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `numero` int DEFAULT NULL,
  `id_equipo` int DEFAULT NULL,
  `imagen_url` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `genero` enum('M','F') COLLATE utf8mb4_general_ci DEFAULT 'M'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `jugadores`
--

INSERT INTO `jugadores` (`id_jugador`, `nombre`, `numero`, `id_equipo`, `imagen_url`, `genero`) VALUES
(1, 'Cristiano Ronaldo', 7, 2, 'static/images/players/realmadrid/cristiano.png', 'M'),
(2, 'Karim Benzema', 9, 2, 'static/images/players/realmadrid/benzema.png', 'M'),
(3, 'Vin?cius Jr.', 7, 2, 'static/images/players/realmadrid/vinicius.png', 'M'),
(8, 'Aitana Bonmat?', 14, 3, 'static/images/players/barcelona/aitana.png', 'F'),
(9, 'Alexia Putellas', 11, 3, 'static/images/players/barcelona/alexia.png', 'F'),
(10, 'Lamine Yamal', 27, 3, 'static/images/players/barcelona/yamal.png', 'M'),
(11, 'Cole Palmer', 20, 4, 'static/images/players/chelsea/palmer.png', 'M'),
(12, 'Erin Cuthbert', 22, 4, 'static/images/players/chelsea/cuthbert.png', 'F'),
(13, 'Reece James', 24, 4, 'static/images/players/chelsea/reece.png', 'M'),
(14, 'Toni Kroos', 8, 1, 'static/images/players/alemania/kroos.png', 'M'),
(15, 'Kai Havertz', 7, 1, 'static/images/players/alemania/havertz.png', 'M'),
(16, 'Leroy San?', 19, 1, 'static/images/players/alemania/sane.png', 'M'),
(17, 'Erling Haaland', 9, 5, 'static/images/players/mancity/haaland.png', 'M'),
(18, 'Kevin De Bruyne', 17, 5, 'static/images/players/mancity/debruyne.png', 'M'),
(19, 'Abby Dahlkemper', 13, 5, 'static/images/players/mancity/dahlkemper.png', 'F');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ordenes_compra`
--

CREATE TABLE `ordenes_compra` (
  `id_orden` int NOT NULL,
  `fecha` datetime DEFAULT CURRENT_TIMESTAMP,
  `id_proveedor` int DEFAULT NULL,
  `estado` enum('pendiente','enviado','recibido') COLLATE utf8mb4_general_ci DEFAULT 'pendiente'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `ordenes_compra`
--

INSERT INTO `ordenes_compra` (`id_orden`, `fecha`, `id_proveedor`, `estado`) VALUES
(17, '2025-07-19 21:53:11', 1, 'pendiente'),
(18, '2025-07-24 07:35:23', 2, 'pendiente');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `orden_detalle`
--

CREATE TABLE `orden_detalle` (
  `id_orden` int DEFAULT NULL,
  `id_jersey` int DEFAULT NULL,
  `id_talla` int DEFAULT NULL,
  `cantidad` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `orden_detalle`
--

INSERT INTO `orden_detalle` (`id_orden`, `id_jersey`, `id_talla`, `cantidad`) VALUES
(17, 4, 4, 5),
(18, 4, 4, 10),
(18, 3, 4, 5);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pagos_clientes`
--

CREATE TABLE `pagos_clientes` (
  `id_pago` int NOT NULL,
  `id_venta` int DEFAULT NULL,
  `monto` decimal(10,2) DEFAULT NULL,
  `fecha` datetime DEFAULT CURRENT_TIMESTAMP,
  `metodo` enum('tarjeta','paypal','efectivo') COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `pagos_clientes`
--

INSERT INTO `pagos_clientes` (`id_pago`, `id_venta`, `monto`, `fecha`, `metodo`) VALUES
(8, 10, 720.00, '2025-07-20 21:56:58', 'tarjeta'),
(9, 11, 699.88, '2025-07-23 15:54:17', 'tarjeta'),
(10, 12, 799.00, '2025-07-24 07:41:56', 'tarjeta'),
(11, 13, 699.88, '2025-09-04 09:17:16', 'tarjeta'),
(12, 14, 799.00, '2025-11-11 11:33:11', 'tarjeta');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pagos_proveedores`
--

CREATE TABLE `pagos_proveedores` (
  `id_pago` int NOT NULL,
  `id_orden` int DEFAULT NULL,
  `monto` decimal(10,2) DEFAULT NULL,
  `fecha` datetime DEFAULT CURRENT_TIMESTAMP,
  `metodo` enum('visa') COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `pagos_proveedores`
--

INSERT INTO `pagos_proveedores` (`id_pago`, `id_orden`, `monto`, `fecha`, `metodo`) VALUES
(12, 17, 3600.00, '2025-07-19 21:53:11', 'visa'),
(13, 18, 10699.40, '2025-07-24 07:35:23', 'visa');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `proveedores`
--

CREATE TABLE `proveedores` (
  `id_proveedor` int NOT NULL,
  `nombre` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `contacto` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `telefono` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `correo` varchar(150) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `direccion` text COLLATE utf8mb4_general_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `proveedores`
--

INSERT INTO `proveedores` (`id_proveedor`, `nombre`, `contacto`, `telefono`, `correo`, `direccion`) VALUES
(1, 'Deportes MX', 'Juan Pérez', '555-1234', 'contacto@deportesmx.com', 'Av. Reforma 123, CDMX'),
(2, 'Adidas', 'Loera', '4494684711', 'rascuacheproveedor@gmail.com', 'Av Lopez Mateos');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tallas`
--

CREATE TABLE `tallas` (
  `id_talla` int NOT NULL,
  `talla` varchar(5) COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tallas`
--

INSERT INTO `tallas` (`id_talla`, `talla`) VALUES
(1, 'S'),
(2, 'M'),
(3, 'L'),
(4, 'XL');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id_usuario` int NOT NULL,
  `nombre` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `correo` varchar(150) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `contrasena` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `fecha_registro` datetime DEFAULT CURRENT_TIMESTAMP,
  `rol` enum('cliente','admin') COLLATE utf8mb4_general_ci DEFAULT 'cliente'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id_usuario`, `nombre`, `correo`, `contrasena`, `fecha_registro`, `rol`) VALUES
(1, 'mapache', 'mapache@gmail.com', '$2y$10$1ftQPfEBBhJRX1UfxK/WaOPOOPHe98Curi/tOF56hA5RFJ9b0mM5m', '2025-07-01 18:07:11', 'cliente'),
(2, 'Rascuache', 'rascuache@gmail.com', '$2y$10$gjin6or.OtyYIGCaa2gq..26jxUSqZrickaMt9VSwRCteLV9RiyUy', '2025-07-01 18:14:46', 'admin'),
(5, 'Puerquis', 'puerquis@gmail.com', '$2y$10$gtzYg9MJSafefUl/IvTIIuiHQT7T3U7KHhpAl0ROj/qhQs5.LFiNu', '2025-07-15 21:46:51', 'cliente'),
(6, 'JOSE', 'lolo@gmail.com', '$2y$10$6MITIklQ33Ytp/0/LeC6O.9c293AmcKwobdbsBOfkPV9nZy/jfWXa', '2025-07-23 15:52:16', 'cliente'),
(7, 'JOSE', 'loera7753@gmail.com', '$2y$10$/n9odT33gn2YjEG0ZHg7aOLyVwD8TVDzKAt0cmE33VVVEjvi9tquC', '2025-07-23 15:53:06', 'cliente'),
(8, 'Moy', 'zcabraham23@gmail.com', '$2y$10$CoFt9C0kxyvsvG747cFEVeoT3jBMrcaY4kK3m/f6hnVZ6Y.JIHKZu', '2025-09-04 09:15:37', 'cliente'),
(9, 'Moy', 'loeraQ@gmail.com', '$2y$10$BqTLVKlXxiGs47iaU2s8ruRVPAwRsUq2WJjwVm.AtyQkiVPZ.wULq', '2025-11-11 11:26:17', 'cliente');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios_p`
--

CREATE TABLE `usuarios_p` (
  `id_usuario` int NOT NULL,
  `nombre` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `correo` varchar(150) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `telefono` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `fecha_registro` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios_p`
--

INSERT INTO `usuarios_p` (`id_usuario`, `nombre`, `correo`, `telefono`, `fecha_registro`) VALUES
(33, 'rascuache', 'rascuache26@gmail.com', '4494684711', '2025-07-14 09:30:35'),
(34, 'JOSE', 'loera7753@gmail.com', '4951367178', '2025-07-23 16:24:10'),
(35, 'JOSE', 'loera7753@gmail.com', '4951367178', '2025-07-23 16:24:15'),
(36, 'jdhgjsdgd', 'jdgjdsds@sjhsdjs.mnb', 'dssldkjlsckjslfk', '2025-07-24 07:30:18'),
(37, 'jdhgjsdgd', 'jdgjdsds@sjhsdjs.mnb', 'dssldkjlsckjslfk', '2025-07-24 07:30:19'),
(38, 'jdhgjsdgd', 'jdgjdsds@sjhsdjs.mnb', 'dssldkjlsckjslfk', '2025-07-24 07:30:21'),
(39, 'jdhgjsdgd', 'jdgjdsds@sjhsdjs.mnb', 'dssldkjlsckjslfk', '2025-07-24 07:30:23'),
(40, 'moy', 'zcabraham23@gmail.com', '4493945893', '2025-09-04 09:13:42');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ventas`
--

CREATE TABLE `ventas` (
  `id_venta` int NOT NULL,
  `id_usuario` int DEFAULT NULL,
  `fecha` datetime DEFAULT CURRENT_TIMESTAMP,
  `estado` enum('pendiente','pagada','enviada','completada') COLLATE utf8mb4_general_ci DEFAULT 'pendiente'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `ventas`
--

INSERT INTO `ventas` (`id_venta`, `id_usuario`, `fecha`, `estado`) VALUES
(10, 1, '2025-07-20 21:56:58', 'pagada'),
(11, 7, '2025-07-23 15:54:17', 'pagada'),
(12, 1, '2025-07-24 07:41:56', 'pagada'),
(13, 8, '2025-09-04 09:17:16', 'pagada'),
(14, 9, '2025-11-11 11:33:11', 'pagada');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `venta_detalle`
--

CREATE TABLE `venta_detalle` (
  `id_venta` int DEFAULT NULL,
  `id_jersey` int DEFAULT NULL,
  `id_talla` int DEFAULT NULL,
  `cantidad` int DEFAULT NULL,
  `precio_unitario` decimal(8,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `venta_detalle`
--

INSERT INTO `venta_detalle` (`id_venta`, `id_jersey`, `id_talla`, `cantidad`, `precio_unitario`) VALUES
(10, 4, 2, 1, 720.00),
(11, 3, 3, 1, 699.88),
(12, 1, 2, 1, 799.00),
(13, 3, 3, 1, 699.88),
(14, 1, 4, 1, 799.00);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `carrito`
--
ALTER TABLE `carrito`
  ADD PRIMARY KEY (`id_carrito`),
  ADD KEY `id_usuario` (`id_usuario`),
  ADD KEY `id_jersey` (`id_jersey`),
  ADD KEY `id_talla` (`id_talla`);

--
-- Indices de la tabla `envios`
--
ALTER TABLE `envios`
  ADD PRIMARY KEY (`id_envio`),
  ADD KEY `envios_ibfk_1` (`id_venta`);

--
-- Indices de la tabla `equipos`
--
ALTER TABLE `equipos`
  ADD PRIMARY KEY (`id_equipo`);

--
-- Indices de la tabla `jerseys`
--
ALTER TABLE `jerseys`
  ADD PRIMARY KEY (`id_jersey`),
  ADD KEY `id_equipo` (`id_equipo`);

--
-- Indices de la tabla `jersey_tallas`
--
ALTER TABLE `jersey_tallas`
  ADD PRIMARY KEY (`id_jersey`,`id_talla`),
  ADD KEY `id_talla` (`id_talla`);

--
-- Indices de la tabla `jugadores`
--
ALTER TABLE `jugadores`
  ADD PRIMARY KEY (`id_jugador`),
  ADD KEY `id_equipo` (`id_equipo`);

--
-- Indices de la tabla `ordenes_compra`
--
ALTER TABLE `ordenes_compra`
  ADD PRIMARY KEY (`id_orden`),
  ADD KEY `id_proveedor` (`id_proveedor`);

--
-- Indices de la tabla `orden_detalle`
--
ALTER TABLE `orden_detalle`
  ADD KEY `id_jersey` (`id_jersey`,`id_talla`),
  ADD KEY `orden_detalle_ibfk_1` (`id_orden`);

--
-- Indices de la tabla `pagos_clientes`
--
ALTER TABLE `pagos_clientes`
  ADD PRIMARY KEY (`id_pago`),
  ADD KEY `pagos_clientes_ibfk_1` (`id_venta`);

--
-- Indices de la tabla `pagos_proveedores`
--
ALTER TABLE `pagos_proveedores`
  ADD PRIMARY KEY (`id_pago`),
  ADD KEY `id_orden` (`id_orden`);

--
-- Indices de la tabla `proveedores`
--
ALTER TABLE `proveedores`
  ADD PRIMARY KEY (`id_proveedor`);

--
-- Indices de la tabla `tallas`
--
ALTER TABLE `tallas`
  ADD PRIMARY KEY (`id_talla`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id_usuario`),
  ADD UNIQUE KEY `correo` (`correo`);

--
-- Indices de la tabla `usuarios_p`
--
ALTER TABLE `usuarios_p`
  ADD PRIMARY KEY (`id_usuario`);

--
-- Indices de la tabla `ventas`
--
ALTER TABLE `ventas`
  ADD PRIMARY KEY (`id_venta`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indices de la tabla `venta_detalle`
--
ALTER TABLE `venta_detalle`
  ADD KEY `id_jersey` (`id_jersey`,`id_talla`),
  ADD KEY `venta_detalle_ibfk_1` (`id_venta`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `carrito`
--
ALTER TABLE `carrito`
  MODIFY `id_carrito` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT de la tabla `envios`
--
ALTER TABLE `envios`
  MODIFY `id_envio` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `equipos`
--
ALTER TABLE `equipos`
  MODIFY `id_equipo` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `jerseys`
--
ALTER TABLE `jerseys`
  MODIFY `id_jersey` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `jugadores`
--
ALTER TABLE `jugadores`
  MODIFY `id_jugador` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT de la tabla `ordenes_compra`
--
ALTER TABLE `ordenes_compra`
  MODIFY `id_orden` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT de la tabla `pagos_clientes`
--
ALTER TABLE `pagos_clientes`
  MODIFY `id_pago` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `pagos_proveedores`
--
ALTER TABLE `pagos_proveedores`
  MODIFY `id_pago` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de la tabla `proveedores`
--
ALTER TABLE `proveedores`
  MODIFY `id_proveedor` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `tallas`
--
ALTER TABLE `tallas`
  MODIFY `id_talla` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id_usuario` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `usuarios_p`
--
ALTER TABLE `usuarios_p`
  MODIFY `id_usuario` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT de la tabla `ventas`
--
ALTER TABLE `ventas`
  MODIFY `id_venta` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `carrito`
--
ALTER TABLE `carrito`
  ADD CONSTRAINT `carrito_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`),
  ADD CONSTRAINT `carrito_ibfk_2` FOREIGN KEY (`id_jersey`) REFERENCES `jerseys` (`id_jersey`),
  ADD CONSTRAINT `carrito_ibfk_3` FOREIGN KEY (`id_talla`) REFERENCES `tallas` (`id_talla`);

--
-- Filtros para la tabla `envios`
--
ALTER TABLE `envios`
  ADD CONSTRAINT `envios_ibfk_1` FOREIGN KEY (`id_venta`) REFERENCES `ventas` (`id_venta`) ON DELETE CASCADE;

--
-- Filtros para la tabla `jerseys`
--
ALTER TABLE `jerseys`
  ADD CONSTRAINT `jerseys_ibfk_1` FOREIGN KEY (`id_equipo`) REFERENCES `equipos` (`id_equipo`);

--
-- Filtros para la tabla `jersey_tallas`
--
ALTER TABLE `jersey_tallas`
  ADD CONSTRAINT `jersey_tallas_ibfk_1` FOREIGN KEY (`id_jersey`) REFERENCES `jerseys` (`id_jersey`),
  ADD CONSTRAINT `jersey_tallas_ibfk_2` FOREIGN KEY (`id_talla`) REFERENCES `tallas` (`id_talla`);

--
-- Filtros para la tabla `jugadores`
--
ALTER TABLE `jugadores`
  ADD CONSTRAINT `jugadores_ibfk_1` FOREIGN KEY (`id_equipo`) REFERENCES `equipos` (`id_equipo`);

--
-- Filtros para la tabla `ordenes_compra`
--
ALTER TABLE `ordenes_compra`
  ADD CONSTRAINT `ordenes_compra_ibfk_1` FOREIGN KEY (`id_proveedor`) REFERENCES `proveedores` (`id_proveedor`);

--
-- Filtros para la tabla `orden_detalle`
--
ALTER TABLE `orden_detalle`
  ADD CONSTRAINT `orden_detalle_ibfk_1` FOREIGN KEY (`id_orden`) REFERENCES `ordenes_compra` (`id_orden`) ON DELETE CASCADE,
  ADD CONSTRAINT `orden_detalle_ibfk_2` FOREIGN KEY (`id_jersey`,`id_talla`) REFERENCES `jersey_tallas` (`id_jersey`, `id_talla`);

--
-- Filtros para la tabla `pagos_clientes`
--
ALTER TABLE `pagos_clientes`
  ADD CONSTRAINT `pagos_clientes_ibfk_1` FOREIGN KEY (`id_venta`) REFERENCES `ventas` (`id_venta`) ON DELETE CASCADE;

--
-- Filtros para la tabla `pagos_proveedores`
--
ALTER TABLE `pagos_proveedores`
  ADD CONSTRAINT `pagos_proveedores_ibfk_1` FOREIGN KEY (`id_orden`) REFERENCES `ordenes_compra` (`id_orden`);

--
-- Filtros para la tabla `ventas`
--
ALTER TABLE `ventas`
  ADD CONSTRAINT `ventas_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`);

--
-- Filtros para la tabla `venta_detalle`
--
ALTER TABLE `venta_detalle`
  ADD CONSTRAINT `venta_detalle_ibfk_1` FOREIGN KEY (`id_venta`) REFERENCES `ventas` (`id_venta`) ON DELETE CASCADE,
  ADD CONSTRAINT `venta_detalle_ibfk_2` FOREIGN KEY (`id_jersey`,`id_talla`) REFERENCES `jersey_tallas` (`id_jersey`, `id_talla`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
