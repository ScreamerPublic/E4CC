# Host: localhost  (Version 8.0.30)
# Date: 2024-04-24 00:53:49
# Generator: MySQL-Front 6.1  (Build 1.26)


#
# Structure for table "registros_pagos"
#

DROP TABLE IF EXISTS `registros_pagos`;
CREATE TABLE `registros_pagos` (
  `id` int NOT NULL AUTO_INCREMENT,
  `tipo_pago` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cantidad` decimal(10,2) NOT NULL,
  `monto_pagar` decimal(10,2) NOT NULL,
  `fecha_pagar` date NOT NULL,
  `comentarios` text COLLATE utf8mb4_unicode_ci,
  `creado_en` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `idusuario` int DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

#
# Data for table "registros_pagos"
#


#
# Structure for table "usuarios"
#

DROP TABLE IF EXISTS `usuarios`;
CREATE TABLE `usuarios` (
  `id_usuario` int NOT NULL AUTO_INCREMENT,
  `nombres` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `apellidos` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `nombre_usuario` varchar(255) NOT NULL,
  `contrasena` varchar(255) NOT NULL,
  `correo_electronico` varchar(255) NOT NULL,
  `estado` enum('activo','inactivo') NOT NULL DEFAULT 'activo',
  `rol` enum('administrador','usuario') NOT NULL DEFAULT 'usuario',
  `fecha_creacion` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `fecha_actualizacion` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_usuario`),
  UNIQUE KEY `nombre_usuario` (`nombre_usuario`),
  UNIQUE KEY `correo_electronico` (`correo_electronico`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

#
# Data for table "usuarios"
#

INSERT INTO `usuarios` VALUES (1,'Diego','Lopez','diego.lopez','$2y$10$v.tAAhJT1u/lps5/qSjmUeZFHi5nZuK5S1Tpp7AVQJSEMNbpuZeYi','diego.arturo.lopez98@gmail.com','activo','administrador','2024-04-23 16:01:32','2024-04-24 00:14:32'),(2,'Prueba','prueba','prueba','$2y$10$mJg7JNBy2d0URpZvx8DTru9hwniZmnvkDEcQygpkAwIm.jBrdA3kq','prueba@gmail.com','inactivo','usuario','2024-04-24 00:30:37','2024-04-24 00:49:37'),(3,'jcvillalta','','jcvillalta','$2y$10$3G1WXDJGYKoTU6wCg7xaj.OdJYoCn0ORAt3N8K1Mr8oEHlSxHClHe','gcvillalta1234@gmail.com','activo','administrador','2024-04-24 00:52:57','2024-04-24 00:52:57');
