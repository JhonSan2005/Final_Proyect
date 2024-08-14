-- Crear base de datos
CREATE DATABASE IF NOT EXISTS `bd_jj` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `bd_jj`;

-- Crear tablas
CREATE TABLE `categorias` (
  `id_categoria` int(11) NOT NULL AUTO_INCREMENT,
  `nombre_categoria` varchar(100) NOT NULL,
  PRIMARY KEY (`id_categoria`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `estado_factura` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `estado` varchar(25) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `roles` (
  `id_rol` int(11) NOT NULL AUTO_INCREMENT,
  `rol` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id_rol`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `usuario` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `documento` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `correo` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `foto_perfil` varchar(255) DEFAULT NULL,
  `id_rol` int(11) NOT NULL DEFAULT 2,
  `token` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `documento` (`documento`),
  UNIQUE KEY `correo` (`correo`),
  KEY `id_rol` (`id_rol`),
  CONSTRAINT `usuario_ibfk_1` FOREIGN KEY (`id_rol`) REFERENCES `roles` (`id_rol`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `productos` (
  `id_producto` int(11) NOT NULL AUTO_INCREMENT,
  `nombre_producto` varchar(100) NOT NULL,
  `precio` decimal(10,2) NOT NULL,
  `impuesto` decimal(10,2) DEFAULT NULL,
  `stock` int(11) NOT NULL,
  `id_categoria` int(11) NOT NULL,
  `descripcion` varchar(1000) NOT NULL,
  `imagen_url` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id_producto`),
  KEY `id_categoria` (`id_categoria`),
  CONSTRAINT `productos_ibfk_1` FOREIGN KEY (`id_categoria`) REFERENCES `categorias` (`id_categoria`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `factura` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `descripcion` text DEFAULT NULL,
  `id_estado` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `fecha_facturacion` datetime NOT NULL,
  `direccion_facturacion` text DEFAULT NULL,
  `impuesto` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_estado_factura` (`id_estado`),
  KEY `fk_factura_usuario` (`id_usuario`),
  CONSTRAINT `fk_estado_factura` FOREIGN KEY (`id_estado`) REFERENCES `estado_factura` (`id`),
  CONSTRAINT `fk_factura_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `pagos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `plataforma` text DEFAULT NULL,
  `id_factura` int(11) NOT NULL,
  `fecha_pago` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_pagos_factura` (`id_factura`),
  CONSTRAINT `fk_pagos_factura` FOREIGN KEY (`id_factura`) REFERENCES `factura` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `ventas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_factura` int(11) NOT NULL,
  `id_producto` int(11) NOT NULL,
  `cantidad` int(5) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_factura_venta` (`id_factura`),
  KEY `fk_venta_producto` (`id_producto`),
  CONSTRAINT `fk_factura_venta` FOREIGN KEY (`id_factura`) REFERENCES `factura` (`id`),
  CONSTRAINT `fk_venta_producto` FOREIGN KEY (`id_producto`) REFERENCES `productos` (`id_producto`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Insertar datos en las tablas

-- Insertar en `categorias`
INSERT INTO `categorias` (`nombre_categoria`) VALUES
('Aceite'),
('Repuestos'),
('Llantas');

-- Insertar en `estado_factura`
INSERT INTO `estado_factura` (`estado`) VALUES
('En Proceso'),
('En Camino'),
('Cancelada'),
('Entregada'),
('En Proceso de Devolucion');

-- Insertar en `roles`
INSERT INTO `roles` (`rol`) VALUES
('Admin'),
('User');

-- Insertar en `usuario` (asegúrate de que la contraseña esté encriptada)
INSERT INTO `usuario` (`documento`, `nombre`, `correo`, `password`, `foto_perfil`, `id_rol`, `token`) VALUES
(123456789, 'Admin', 'admin@onion.com', '$2y$10$PpkZqFoT0nYBzDFsvwx5.eTV9rQ3qiP44iT/nMjlJW2.ntkXhYVtu', NULL, 1, ''),
(987654321, 'User', 'user@onion.com', '$2y$10$examplehashedpassword', NULL, 2, '');

-- Insertar en `productos`
INSERT INTO `productos` (`nombre_producto`, `precio`, `impuesto`, `stock`, `id_categoria`, `descripcion`, `imagen_url`) VALUES
('Yamalube', 40.00, 16.00, 24, 1, 'Aceite de para motos de cilindraje 125cc y 250cc', '/img/uploads/1.png'),
('Acetavo', 46.00, 16.00, 26, 1, 'Aceite de Sinteticio 125cc', '/img/uploads/2.png'),
('Motul', 45.00, 16.00, 27, 1, 'Aceite de para motos 125cc-Cricton Fi', '/img/uploads/3.png'),
('Filtro de Aire', 55.00, 16.00, 27, 1, 'Filtro maxima capacidad Cortina Plana', '/img/uploads/4.png'),
('Bujia Iridum', 80.00, 16.00, 30, 1, 'Bujia Mejorada optimo rendimiento', '/img/uploads/5.png'),
('Cadena KMC', 57.00, 16.00, 30, 1, 'Cadena de Cricton-Fi', '/img/uploads/6.png'),
('Llantas FZL6', 250.00, 16.00, 30, 1, 'Diseñadas para brindar un agarre superior y una conducción suave', '/img/uploads/7.png'),
('Llantas XTZ125', 239.00, 16.00, 30, 1, 'Perfectas para los terreno mas ostiles', '/img/uploads/8.png'),
('Llantas NKD-125', 200.00, 16.00, 30, 1, 'Te brindan comodidad para tu dia a dia conduccion suave', '/img/uploads/9.png');

-- Insertar en `factura`
INSERT INTO `factura` (`descripcion`, `id_estado`, `id_usuario`, `fecha_facturacion`, `direccion_facturacion`, `impuesto`) VALUES
('Factura de Venta', 1, 1, '2024-08-12 10:08:43', 'Carrera 24 # 20 - 04 - San José del Guaviare, Guaviare, Colombia', 19);

-- Insertar en `ventas`
INSERT INTO `ventas` (`id_factura`, `id_producto`, `cantidad`) VALUES
(1, 1, 3),
(1, 2, 4),
(1, 3, 3),
(1, 4, 3);

-- Insertar en `pagos`
INSERT INTO `pagos` (`id_factura`, `fecha_pago`) VALUES
(1, '2024-08-12 10:08:43');
