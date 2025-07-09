-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Versión del servidor:         10.4.32-MariaDB - mariadb.org binary distribution
-- SO del servidor:              Win64
-- HeidiSQL Versión:             12.11.0.7065
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Volcando estructura de base de datos para facturacion
CREATE DATABASE IF NOT EXISTS `facturacion` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci */;
USE `facturacion`;

-- Volcando estructura para tabla facturacion.categorias
CREATE TABLE IF NOT EXISTS `categorias` (
  `id_categoria` int(11) NOT NULL AUTO_INCREMENT,
  `nombre_categoria` varchar(100) NOT NULL,
  `descripcion` text NOT NULL,
  `estado` enum('activo','inactivo','eliminado') NOT NULL DEFAULT 'activo',
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id_categoria`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla facturacion.categorias: ~21 rows (aproximadamente)
INSERT INTO `categorias` (`id_categoria`, `nombre_categoria`, `descripcion`, `estado`, `fecha_creacion`) VALUES
	(1, 'Aceites', 'Categoría de Aceites', 'activo', '2025-06-11 02:44:27'),
	(2, 'Aditivos', 'Categoría de Aditivos', 'activo', '2025-06-11 02:44:27'),
	(3, 'Alternadores', 'Categoría de Alternadores', 'activo', '2025-06-11 02:44:27'),
	(4, 'Amortiguadores', 'Categoría de Amortiguadores', 'activo', '2025-06-11 02:44:27'),
	(5, 'Arrancadores', 'Categoría de Arrancadores', 'activo', '2025-06-11 02:44:27'),
	(6, 'Baterías', 'Categoría de Baterías', 'activo', '2025-06-11 02:44:27'),
	(7, 'Bombillas', 'Categoría de Bombillas', 'activo', '2025-06-11 02:44:27'),
	(8, 'Bujías', 'Categoría de Bujías', 'activo', '2025-06-11 02:44:27'),
	(9, 'Correas', 'Categoría de Correas', 'activo', '2025-06-11 02:44:27'),
	(10, 'Espejos', 'Categoría de Espejos', 'activo', '2025-06-11 02:44:27'),
	(11, 'Filtros de Aceite', 'Categoría de Filtros de Aceite', 'activo', '2025-06-11 02:44:27'),
	(12, 'Filtros de Aire', 'Categoría de Filtros de Aire', 'activo', '2025-06-11 02:44:27'),
	(13, 'Inyectores', 'Categoría de Inyectores', 'activo', '2025-06-11 02:44:27'),
	(14, 'Limpiaparabrisas', 'Categoría de Limpiaparabrisas', 'activo', '2025-06-11 02:44:27'),
	(15, 'Lubricantes', 'Categoría de Lubricantes', 'activo', '2025-06-11 02:44:27'),
	(16, 'Pastillas de Freno', 'Categoría de Pastillas de Freno', 'activo', '2025-06-11 02:44:27'),
	(17, 'Radiadores', 'Categoría de Radiadores', 'activo', '2025-06-11 02:44:27'),
	(18, 'Refrigerantes', 'Categoría de Refrigerantes', 'activo', '2025-06-11 02:44:27'),
	(19, 'Sensores', 'Categoría de Sensores', 'activo', '2025-06-11 02:44:27'),
	(20, 'Turbos', 'Categoría de Turbos', 'activo', '2025-06-11 02:44:27'),
	(21, 'XXX', 'ZZZZ', 'inactivo', '2025-06-13 01:00:18');

-- Volcando estructura para tabla facturacion.certificados
CREATE TABLE IF NOT EXISTS `certificados` (
  `id_certificado` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Identificador único del certificado digital',
  `nombre` varchar(100) NOT NULL COMMENT 'Nombre del certificado',
  `ruta_archivo` text NOT NULL COMMENT 'Ruta del archivo .p12 del certificado',
  `clave` varchar(256) NOT NULL COMMENT 'Clave del certificado encriptada',
  `fecha_inicio` date NOT NULL COMMENT 'Inicio de la vigencia del certificado',
  `fecha_fin` date NOT NULL COMMENT 'Fin de la vigencia del certificado',
  `id_usuario` int(11) unsigned DEFAULT NULL COMMENT 'ID del usuario al que pertenece el certificado',
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp() COMMENT 'Fecha de registro del certificado',
  `estado_registro` enum('activo','inactivo','eliminado') DEFAULT 'activo' COMMENT 'Estado lógico del certificado',
  `fecha_modificacion` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp() COMMENT 'Última modificación del certificado',
  `usuario_creacion` int(11) unsigned DEFAULT NULL COMMENT 'ID del usuario que creó el registro',
  `usuario_modificacion` int(11) unsigned DEFAULT NULL COMMENT 'ID del usuario que modificó el registro',
  `origen_dato` varchar(100) DEFAULT NULL COMMENT 'Origen del dato',
  PRIMARY KEY (`id_certificado`),
  KEY `id_usuario` (`id_usuario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla facturacion.certificados: ~0 rows (aproximadamente)

-- Volcando estructura para tabla facturacion.clientes
CREATE TABLE IF NOT EXISTS `clientes` (
  `id_cliente` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Identificador único del cliente',
  `razon_social` varchar(150) NOT NULL COMMENT 'Nombre o razón social del cliente',
  `ruc_cedula` varchar(20) NOT NULL COMMENT 'Identificación fiscal del cliente',
  `tipo_identificacion` enum('RUC','Cedula','Pasaporte') NOT NULL COMMENT 'Tipo de documento de identificación',
  `direccion` text DEFAULT NULL COMMENT 'Dirección física del cliente',
  `telefono` varchar(20) DEFAULT NULL COMMENT 'Número de teléfono del cliente',
  `correo` varchar(150) DEFAULT NULL COMMENT 'Correo electrónico del cliente',
  `estado` enum('activo','inactivo') DEFAULT 'activo' COMMENT 'Estado actual del cliente',
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp() COMMENT 'Fecha de registro del cliente',
  `estado_registro` enum('activo','inactivo','eliminado') DEFAULT 'activo' COMMENT 'Estado lógico del cliente',
  `fecha_modificacion` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp() COMMENT 'Última fecha de modificación',
  `usuario_creacion` int(11) DEFAULT NULL COMMENT 'ID del usuario que creó el registro',
  `usuario_modificacion` int(11) DEFAULT NULL COMMENT 'ID del usuario que modificó el registro',
  `origen_dato` varchar(100) DEFAULT NULL COMMENT 'Origen del dato',
  PRIMARY KEY (`id_cliente`)
) ENGINE=InnoDB AUTO_INCREMENT=41 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla facturacion.clientes: ~40 rows (aproximadamente)
INSERT INTO `clientes` (`id_cliente`, `razon_social`, `ruc_cedula`, `tipo_identificacion`, `direccion`, `telefono`, `correo`, `estado`, `fecha_registro`, `estado_registro`, `fecha_modificacion`, `usuario_creacion`, `usuario_modificacion`, `origen_dato`) VALUES
	(1, 'Constructora Andina S.A.', '1790012345001', 'RUC', 'Av. Amazonas N34-123 y Patria', '0998123456', 'contacto@andina.com.ec', 'activo', '2025-06-12 04:36:47', 'activo', NULL, 1, NULL, 'manual'),
	(2, 'Juan Pérez', '1712345678', 'Cedula', 'Calle 10 de Agosto N12-45', '0987654321', 'juan.perez@mail.com', 'activo', '2025-06-12 04:36:47', 'activo', NULL, 1, NULL, 'manual'),
	(3, 'Distribuidora Soluciones', '1790098765001', 'RUC', 'Av. Naciones Unidas 145', '0999988776', 'ventas@soluciones.com.ec', 'activo', '2025-06-12 04:36:47', 'activo', NULL, 1, NULL, 'manual'),
	(4, 'María López', '1711122233', 'Cedula', 'Calle Sucre N15-67', '0987123456', 'mlopez@mail.com', 'activo', '2025-06-12 04:36:47', 'activo', NULL, 1, NULL, 'manual'),
	(5, 'Electrodomésticos Rivera', '1790087654001', 'RUC', 'Av. 6 de Diciembre y Shyris', '0999111222', 'info@rivera.com.ec', 'activo', '2025-06-12 04:36:47', 'activo', NULL, 1, NULL, 'manual'),
	(6, 'Carlos Sánchez', '1713344556', 'Cedula', 'Av. Patria y Gaspar de Villarroel', '0987999887', 'c.sanchez@mail.com', 'activo', '2025-06-12 04:36:47', 'activo', NULL, 1, NULL, 'manual'),
	(7, 'Ferretería La Moderna', '1790076543001', 'RUC', 'Av. Gran Colombia 123', '0998777665', 'contacto@lamoderna.com', 'activo', '2025-06-12 04:36:47', 'activo', NULL, 1, NULL, 'manual'),
	(8, 'Luis Martínez', '1712233445', 'Cedula', 'Calle Guayaquil N25-89', '0987665544', 'lmartinez@mail.com', 'activo', '2025-06-12 04:36:47', 'activo', NULL, 1, NULL, 'manual'),
	(9, 'Textiles Ecuador', '1790065432001', 'RUC', 'Av. Loja y 10 de Agosto', '0998555443', 'ventas@textilesec.com.ec', 'activo', '2025-06-12 04:36:47', 'activo', NULL, 1, NULL, 'manual'),
	(10, 'Ana Gómez', '1714455667', 'Cedula', 'Calle Olmedo N17-54', '0987443322', 'agomez@mail.com', 'activo', '2025-06-12 04:36:47', 'activo', NULL, 1, NULL, 'manual'),
	(11, 'Papelería Central', '1790054321001', 'RUC', 'Av. 12 de Octubre 456', '0998332211', 'contacto@papeleria.com', 'activo', '2025-06-12 04:36:47', 'activo', NULL, 1, NULL, 'manual'),
	(12, 'Jorge Ramírez', '1715566778', 'Cedula', 'Calle Espejo N20-76', '0987221100', 'jramirez@mail.com', 'activo', '2025-06-12 04:36:47', 'activo', NULL, 1, NULL, 'manual'),
	(13, 'Consultora Integral', '1790043210001', 'RUC', 'Av. Colón 321', '0998111009', 'info@consultora.com.ec', 'activo', '2025-06-12 04:36:47', 'activo', NULL, 1, NULL, 'manual'),
	(14, 'Sofía Torres', '1716677889', 'Cedula', 'Calle Bolívar N22-88', '0987009988', 'storres@mail.com', 'activo', '2025-06-12 04:36:47', 'activo', NULL, 1, NULL, 'manual'),
	(15, 'Alimentos La Finca', '1790032109001', 'RUC', 'Av. 9 de Octubre 789', '0997998877', 'ventas@lafinca.com.ec', 'activo', '2025-06-12 04:36:47', 'activo', NULL, 1, NULL, 'manual'),
	(16, 'Miguel Herrera', '1717788990', 'Cedula', 'Calle Rocafuerte N30-12', '0987887766', 'mherrera@mail.com', 'activo', '2025-06-12 04:36:47', 'activo', NULL, 1, NULL, 'manual'),
	(17, 'Farmacias Salud', '1790021098001', 'RUC', 'Av. 24 de Mayo 654', '0997886655', 'contacto@farmaciasalud.com', 'activo', '2025-06-12 04:36:47', 'activo', NULL, 1, NULL, 'manual'),
	(18, 'Patricia Jiménez', '1718899001', 'Cedula', 'Calle Lizardo García N40-34', '0987665544', 'pjimenez@mail.com', 'activo', '2025-06-12 04:36:47', 'activo', NULL, 1, NULL, 'manual'),
	(19, 'Transportes Rápidos', '1790010987001', 'RUC', 'Av. Quito 123', '0997775544', 'info@transportesrapidos.com', 'activo', '2025-06-12 04:36:47', 'activo', NULL, 1, NULL, 'manual'),
	(20, 'José Fernández', '1719900112', 'Cedula', 'Calle Rocafuerte N50-56', '0987554433', 'jfernandez@mail.com', 'activo', '2025-06-12 04:36:47', 'activo', NULL, 1, NULL, 'manual'),
	(21, 'Importadora Andina', '1790112233445', 'RUC', 'Av. Guayaquil 234', '0997443322', 'importadora@andina.com.ec', 'activo', '2025-06-12 04:36:47', 'activo', NULL, 1, NULL, 'manual'),
	(22, 'Andrea Morales', '1711011121', 'Cedula', 'Calle Colón N60-78', '0987332211', 'amorales@mail.com', 'activo', '2025-06-12 04:36:47', 'activo', NULL, 1, NULL, 'manual'),
	(23, 'Servicios Globales', '1790223344556', 'RUC', 'Av. Amazonas 345', '0997221100', 'servicios@globales.com.ec', 'activo', '2025-06-12 04:36:47', 'activo', NULL, 1, NULL, 'manual'),
	(24, 'Diego Castro', '1712122232', 'Cedula', 'Calle Loja N70-90', '0987111009', 'dcastro@mail.com', 'activo', '2025-06-12 04:36:47', 'activo', NULL, 1, NULL, 'manual'),
	(25, 'Agroindustria La Esperanza', '1790334455667', 'RUC', 'Av. Naciones Unidas 456', '0997009988', 'ventas@esperanza.com.ec', 'activo', '2025-06-12 04:36:47', 'activo', NULL, 1, NULL, 'manual'),
	(26, 'Laura Ruiz', '1713233343', 'Cedula', 'Calle 9 de Octubre N80-12', '0987008877', 'lruiz@mail.com', 'activo', '2025-06-12 04:36:47', 'activo', NULL, 1, NULL, 'manual'),
	(27, 'Tecnología Avanzada', '1790445566778', 'RUC', 'Av. 6 de Diciembre 567', '0996887766', 'contacto@tecnologia.com.ec', 'activo', '2025-06-12 04:36:47', 'activo', NULL, 1, NULL, 'manual'),
	(28, 'Roberto Vega', '1714344454', 'Cedula', 'Calle Sucre N90-34', '0986776655', 'rvega@mail.com', 'activo', '2025-06-12 04:36:47', 'activo', NULL, 1, NULL, 'manual'),
	(29, 'Editorial Letras', '1790556677889', 'RUC', 'Av. Patria 678', '0996775544', 'ventas@letras.com.ec', 'activo', '2025-06-12 04:36:47', 'activo', NULL, 1, NULL, 'manual'),
	(30, 'Elena Castillo', '1715455565', 'Cedula', 'Calle Bolívar N100-56', '0986664433', 'ecastillo@mail.com', 'activo', '2025-06-12 04:36:47', 'activo', NULL, 1, NULL, 'manual'),
	(31, 'Distribuciones Express', '1790667788990', 'RUC', 'Av. Gran Colombia 789', '0996554433', 'contacto@distribuciones.com.ec', 'activo', '2025-06-12 04:36:47', 'activo', NULL, 1, NULL, 'manual'),
	(32, 'Marcos Soto', '1716566676', 'Cedula', 'Calle Guayaquil N110-78', '0986443322', 'msoto@mail.com', 'activo', '2025-06-12 04:36:47', 'activo', NULL, 1, NULL, 'manual'),
	(33, 'Servicios Integrales', '1790778899001', 'RUC', 'Av. Loja 890', '0996332211', 'info@serviciosintegrales.com', 'activo', '2025-06-12 04:36:47', 'activo', NULL, 1, NULL, 'manual'),
	(34, 'Diana Flores', '1717677787', 'Cedula', 'Calle Olmedo N120-90', '0986221100', 'dflores@mail.com', 'activo', '2025-06-12 04:36:47', 'activo', NULL, 1, NULL, 'manual'),
	(35, 'Comercial La Plaza', '1790889900112', 'RUC', 'Av. 12 de Octubre 901', '0996221100', 'ventas@laplaza.com.ec', 'activo', '2025-06-12 04:36:47', 'activo', NULL, 1, NULL, 'manual'),
	(36, 'Santiago Ruiz', '1718788898', 'Cedula', 'Calle Espejo N130-12', '0986111009', 'sruiz@mail.com', 'activo', '2025-06-12 04:36:47', 'activo', NULL, 1, NULL, 'manual'),
	(37, 'Laboratorios Farma', '1790990011223', 'RUC', 'Av. Colón 234', '0996110098', 'contacto@farma.com.ec', 'activo', '2025-06-12 04:36:47', 'activo', NULL, 1, NULL, 'manual'),
	(38, 'Patricia Díaz', '1719899909', 'Cedula', 'Calle Bolívar N140-34', '0986009988', 'pdiaz@mail.com', 'activo', '2025-06-12 04:36:47', 'activo', NULL, 1, NULL, 'manual'),
	(39, 'Inversiones Global', '1791001122334', 'RUC', 'Av. 9 de Octubre 345', '0996008877', 'info@inversiones.com.ec', 'activo', '2025-06-12 04:36:47', 'activo', NULL, 1, NULL, 'manual'),
	(40, 'Esteban Mendoza', '1710900112', 'Cedula', 'Calle Rocafuerte N150-56', '0985997766', 'emendoza@mail.com', 'activo', '2025-06-12 04:36:47', 'activo', '2025-06-15 16:26:35', 1, NULL, 'manual');

-- Volcando estructura para tabla facturacion.detalle_factura
CREATE TABLE IF NOT EXISTS `detalle_factura` (
  `id_detalle` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Identificador del detalle de factura',
  `id_factura` int(11) DEFAULT NULL COMMENT 'ID de la factura relacionada',
  `id_producto` int(11) DEFAULT NULL COMMENT 'ID del producto facturado',
  `cantidad` int(11) NOT NULL COMMENT 'Cantidad vendida del producto',
  `precio_unitario` decimal(10,2) NOT NULL COMMENT 'Precio por unidad del producto',
  `total_linea` decimal(10,2) NOT NULL COMMENT 'Total de la línea de detalle',
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp() COMMENT 'Fecha de registro del detalle',
  `estado_registro` enum('activo','inactivo','eliminado') DEFAULT 'activo' COMMENT 'Estado lógico del detalle',
  `fecha_modificacion` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp() COMMENT 'Última modificación',
  `usuario_creacion` int(11) DEFAULT NULL COMMENT 'ID del usuario que creó el registro',
  `usuario_modificacion` int(11) DEFAULT NULL COMMENT 'ID del usuario que modificó el registro',
  `origen_dato` varchar(100) DEFAULT NULL COMMENT 'Origen del dato',
  PRIMARY KEY (`id_detalle`),
  KEY `idx_detalle_factura` (`id_factura`),
  KEY `idx_detalle_producto` (`id_producto`),
  CONSTRAINT `detalle_factura_ibfk_1` FOREIGN KEY (`id_factura`) REFERENCES `facturas` (`id_factura`),
  CONSTRAINT `detalle_factura_ibfk_2` FOREIGN KEY (`id_producto`) REFERENCES `productos` (`id_producto`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla facturacion.detalle_factura: ~0 rows (aproximadamente)

-- Volcando estructura para tabla facturacion.facturas
CREATE TABLE IF NOT EXISTS `facturas` (
  `id_factura` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Identificador único de la factura',
  `establecimiento` varchar(3) NOT NULL COMMENT 'Código establecimiento (ejemplo: 001)',
  `punto_emision` varchar(3) NOT NULL COMMENT 'Código punto de emisión (ejemplo: 001)',
  `secuencia` int(9) NOT NULL COMMENT 'Número de factura secuencial',
  `numero_factura` varchar(20) GENERATED ALWAYS AS (concat(lpad(`establecimiento`,3,'0'),'-',lpad(`punto_emision`,3,'0'),'-',lpad(`secuencia`,9,'0'))) STORED COMMENT 'Número de factura en formato 001-001-000000123',
  `clave_acceso` varchar(49) NOT NULL COMMENT 'Clave de acceso generada para el SRI',
  `codigo_autorizacion` varchar(49) DEFAULT NULL COMMENT 'Código de autorización devuelto por el SRI',
  `fecha_emision` timestamp NOT NULL DEFAULT current_timestamp() COMMENT 'Fecha de emisión de la factura',
  `id_cliente` int(11) DEFAULT NULL COMMENT 'ID del cliente asociado',
  `id_usuario` int(11) DEFAULT NULL COMMENT 'ID del usuario que emitió la factura',
  `subtotal` decimal(10,2) NOT NULL COMMENT 'Subtotal sin impuestos',
  `iva` decimal(10,2) NOT NULL COMMENT 'Valor del IVA',
  `total` decimal(10,2) NOT NULL COMMENT 'Total de la factura',
  `estado` enum('emitida','firmada','enviada','autorizada','anulada') NOT NULL DEFAULT 'emitida' COMMENT 'Estado del proceso de la factura',
  `tipo_emision` enum('normal','contingencia') NOT NULL DEFAULT 'normal' COMMENT 'Tipo de emisión de la factura',
  `ambiente` enum('pruebas','produccion') NOT NULL DEFAULT 'pruebas' COMMENT 'Ambiente de emisión',
  `ruta_xml_generado` text DEFAULT NULL COMMENT 'Ruta del archivo XML generado',
  `ruta_pdf` text DEFAULT NULL COMMENT 'Ruta del archivo PDF (RIDE) generado',
  `mensaje_sri` text DEFAULT NULL COMMENT 'Mensaje devuelto por el SRI',
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp() COMMENT 'Fecha de registro de la factura',
  `fecha_modificacion` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp() COMMENT 'Última fecha de modificación',
  `estado_registro` enum('activo','inactivo','eliminado') DEFAULT 'activo' COMMENT 'Estado lógico del registro',
  `usuario_creacion` int(11) DEFAULT NULL COMMENT 'ID del usuario que creó el registro',
  `usuario_modificacion` int(11) DEFAULT NULL COMMENT 'ID del usuario que modificó el registro',
  `origen_dato` varchar(100) DEFAULT NULL COMMENT 'Origen del dato',
  PRIMARY KEY (`id_factura`) USING BTREE,
  UNIQUE KEY `unq_num_factura` (`establecimiento`,`punto_emision`,`secuencia`),
  KEY `idx_facturas_cliente` (`id_cliente`),
  KEY `idx_facturas_usuario` (`id_usuario`),
  CONSTRAINT `facturas_ibfk_1` FOREIGN KEY (`id_cliente`) REFERENCES `clientes` (`id_cliente`),
  CONSTRAINT `facturas_ibfk_2` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla facturacion.facturas: ~0 rows (aproximadamente)

-- Volcando estructura para tabla facturacion.parametros_sri
CREATE TABLE IF NOT EXISTS `parametros_sri` (
  `id_parametro` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Identificador único del conjunto de parámetros',
  `ambiente` enum('pruebas','produccion') NOT NULL COMMENT 'Ambiente de trabajo del SRI',
  `url_autorizacion` text NOT NULL COMMENT 'URL de autorización del SRI',
  `url_recepcion` text NOT NULL COMMENT 'URL de recepción del SRI',
  `dominio_produccion` varchar(255) DEFAULT 'https://cel.sri.gob.ec' COMMENT 'Dominio base del SRI para producción',
  `dominio_pruebas` varchar(255) DEFAULT 'https://pruebas.sri.gob.ec' COMMENT 'Dominio base del SRI para pruebas',
  `ruc_emisor` varchar(20) NOT NULL COMMENT 'RUC del emisor de la factura electrónica',
  `razon_social` varchar(150) NOT NULL COMMENT 'Razón social del emisor',
  `nombre_comercial` varchar(150) DEFAULT NULL COMMENT 'Nombre comercial del emisor',
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp() COMMENT 'Fecha de registro del parámetro',
  `estado_registro` enum('activo','inactivo','eliminado') DEFAULT 'activo' COMMENT 'Estado lógico del parámetro',
  `fecha_modificacion` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp() COMMENT 'Última modificación del parámetro',
  `usuario_creacion` int(11) unsigned DEFAULT NULL COMMENT 'ID del usuario que creó el registro',
  `usuario_modificacion` int(11) unsigned DEFAULT NULL COMMENT 'ID del usuario que modificó el registro',
  `origen_dato` varchar(100) DEFAULT NULL COMMENT 'Origen del dato',
  PRIMARY KEY (`id_parametro`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla facturacion.parametros_sri: ~1 rows (aproximadamente)
INSERT INTO `parametros_sri` (`id_parametro`, `ambiente`, `url_autorizacion`, `url_recepcion`, `dominio_produccion`, `dominio_pruebas`, `ruc_emisor`, `razon_social`, `nombre_comercial`, `fecha_registro`, `estado_registro`, `fecha_modificacion`, `usuario_creacion`, `usuario_modificacion`, `origen_dato`) VALUES
	(1, 'produccion', '/comprobantes-electronicos-ws/AutorizacionComprobantes?wsdl', '/comprobantes-electronicos-ws/RecepcionComprobantes?wsdl', 'https://cel.sri.gob.ec', 'https://pruebas.sri.gob.ec', '1799999999001', 'EMPRESA DE OTRO S.A.', 'COMERCIAL EJEMPLO', '2025-06-14 20:07:57', 'activo', '2025-06-14 23:13:37', 1, 1, 'registro inicial');

-- Volcando estructura para tabla facturacion.productos
CREATE TABLE IF NOT EXISTS `productos` (
  `id_producto` int(11) NOT NULL AUTO_INCREMENT,
  `codigo` varchar(20) NOT NULL,
  `nombre` varchar(150) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `id_categoria` int(11) DEFAULT NULL,
  `precio_unitario` decimal(10,2) DEFAULT NULL,
  `stock` int(11) DEFAULT NULL,
  `estado` enum('activo','inactivo','eliminado') DEFAULT 'activo',
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id_producto`) USING BTREE,
  UNIQUE KEY `codigo` (`codigo`) USING BTREE,
  KEY `id_categoria` (`id_categoria`) USING BTREE,
  CONSTRAINT `productos_ibfk_1` FOREIGN KEY (`id_categoria`) REFERENCES `categorias` (`id_categoria`)
) ENGINE=InnoDB AUTO_INCREMENT=104 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla facturacion.productos: ~103 rows (aproximadamente)
INSERT INTO `productos` (`id_producto`, `codigo`, `nombre`, `descripcion`, `id_categoria`, `precio_unitario`, `stock`, `estado`, `fecha_creacion`) VALUES
	(1, 'PRD0001', 'Limpiaparabrisas Modelo 591', 'Limpiaparabrisas de alta calidad para vehículos ligeros y pesados', 14, 214.82, 70, 'activo', '2025-06-12 03:03:40'),
	(2, 'PRD0002', 'Inyectores Modelo 815', 'Inyectores de alta calidad para vehículos ligeros y pesados', 13, 224.71, 42, 'activo', '2025-06-12 03:03:40'),
	(3, 'PRD0003', 'Pastillas de Freno Modelo 776', 'Pastillas de Freno de alta calidad para vehículos ligeros y pesados', 16, 10.18, 92, 'activo', '2025-06-12 03:03:40'),
	(4, 'PRD0004', 'Espejos Modelo 384', 'Espejos de alta calidad para vehículos ligeros y pesados', 10, 118.02, 43, 'activo', '2025-06-12 03:03:40'),
	(5, 'PRD0005', 'Lubricantes Modelo 557', 'Lubricantes de alta calidad para vehículos ligeros y pesados', 15, 21.32, 64, 'activo', '2025-06-12 03:03:40'),
	(6, 'PRD0006', 'Correas Modelo 930', 'Correas de alta calidad para vehículos ligeros y pesados', 9, 223.29, 97, 'activo', '2025-06-12 03:03:40'),
	(7, 'PRD0007', 'Aceites Modelo 869', 'Aceites de alta calidad para vehículos ligeros y pesados', 1, 23.59, 48, 'activo', '2025-06-12 03:03:40'),
	(8, 'PRD0008', 'Aditivos Modelo 266', 'Aditivos de alta calidad para vehículos ligeros y pesados', 2, 108.85, 9, 'activo', '2025-06-12 03:03:40'),
	(9, 'PRD0009', 'Limpiaparabrisas Modelo 925', 'Limpiaparabrisas de alta calidad para vehículos ligeros y pesados', 14, 54.87, 29, 'activo', '2025-06-12 03:03:40'),
	(10, 'PRD0010', 'Alternadores Modelo 121', 'Alternadores de alta calidad para vehículos ligeros y pesados', 3, 215.86, 51, 'activo', '2025-06-12 03:03:40'),
	(11, 'PRD0011', 'Correas Modelo 272', 'Correas de alta calidad para vehículos ligeros y pesados', 9, 81.25, 19, 'activo', '2025-06-12 03:03:40'),
	(12, 'PRD0012', 'Aceites Modelo 922', 'Aceites de alta calidad para vehículos ligeros y pesados', 1, 134.63, 68, 'activo', '2025-06-12 03:03:40'),
	(13, 'PRD0013', 'Bombillas Modelo 949', 'Bombillas de alta calidad para vehículos ligeros y pesados', 7, 274.18, 98, 'activo', '2025-06-12 03:03:40'),
	(14, 'PRD0014', 'Refrigerantes Modelo 571', 'Refrigerantes de alta calidad para vehículos ligeros y pesados', 18, 247.46, 45, 'activo', '2025-06-12 03:03:40'),
	(15, 'PRD0015', 'Filtros de Aceite Modelo 233', 'Filtros de Aceite de alta calidad para vehículos ligeros y pesados', 11, 69.09, 16, 'activo', '2025-06-12 03:03:40'),
	(16, 'PRD0016', 'Arrancadores Modelo 525', 'Arrancadores de alta calidad para vehículos ligeros y pesados', 5, 130.97, 69, 'activo', '2025-06-12 03:03:40'),
	(17, 'PRD0017', 'Pastillas de Freno Modelo 294', 'Pastillas de Freno de alta calidad para vehículos ligeros y pesados', 16, 82.99, 68, 'activo', '2025-06-12 03:03:40'),
	(18, 'PRD0018', 'Espejos Modelo 144', 'Espejos de alta calidad para vehículos ligeros y pesados', 10, 119.84, 71, 'activo', '2025-06-12 03:03:40'),
	(19, 'PRD0019', 'Aditivos Modelo 685', 'Aditivos de alta calidad para vehículos ligeros y pesados', 2, 235.36, 74, 'activo', '2025-06-12 03:03:40'),
	(20, 'PRD0020', 'Baterías Modelo 276', 'Baterías de alta calidad para vehículos ligeros y pesados', 6, 58.15, 71, 'activo', '2025-06-12 03:03:40'),
	(21, 'PRD0021', 'Amortiguadores Modelo 355', 'Amortiguadores de alta calidad para vehículos ligeros y pesados', 4, 33.26, 33, 'activo', '2025-06-12 03:03:40'),
	(22, 'PRD0022', 'Bombillas Modelo 581', 'Bombillas de alta calidad para vehículos ligeros y pesados', 7, 88.56, 75, 'activo', '2025-06-12 03:03:40'),
	(23, 'PRD0023', 'Inyectores Modelo 989', 'Inyectores de alta calidad para vehículos ligeros y pesados', 13, 11.02, 42, 'activo', '2025-06-12 03:03:40'),
	(24, 'PRD0024', 'Inyectores Modelo 606', 'Inyectores de alta calidad para vehículos ligeros y pesados', 13, 235.39, 54, 'activo', '2025-06-12 03:03:40'),
	(25, 'PRD0025', 'Inyectores Modelo 234', 'Inyectores de alta calidad para vehículos ligeros y pesados', 13, 298.50, 44, 'activo', '2025-06-12 03:03:40'),
	(26, 'PRD0026', 'Sensores Modelo 950', 'Sensores de alta calidad para vehículos ligeros y pesados', 19, 222.96, 71, 'activo', '2025-06-12 03:03:40'),
	(27, 'PRD0027', 'Amortiguadores Modelo 154', 'Amortiguadores de alta calidad para vehículos ligeros y pesados', 4, 163.31, 25, 'activo', '2025-06-12 03:03:40'),
	(28, 'PRD0028', 'Bujías Modelo 789', 'Bujías de alta calidad para vehículos ligeros y pesados', 8, 250.31, 62, 'activo', '2025-06-12 03:03:40'),
	(29, 'PRD0029', 'Pastillas de Freno Modelo 420', 'Pastillas de Freno de alta calidad para vehículos ligeros y pesados', 16, 160.22, 52, 'activo', '2025-06-12 03:03:40'),
	(30, 'PRD0030', 'Pastillas de Freno Modelo 248', 'Pastillas de Freno de alta calidad para vehículos ligeros y pesados', 16, 26.25, 76, 'activo', '2025-06-12 03:03:40'),
	(31, 'PRD0031', 'Refrigerantes Modelo 817', 'Refrigerantes de alta calidad para vehículos ligeros y pesados', 18, 294.63, 26, 'activo', '2025-06-12 03:03:40'),
	(32, 'PRD0032', 'Pastillas de Freno Modelo 361', 'Pastillas de Freno de alta calidad para vehículos ligeros y pesados', 16, 244.71, 8, 'activo', '2025-06-12 03:03:40'),
	(33, 'PRD0033', 'Alternadores Modelo 322', 'Alternadores de alta calidad para vehículos ligeros y pesados', 3, 153.15, 77, 'activo', '2025-06-12 03:03:40'),
	(34, 'PRD0034', 'Aditivos Modelo 582', 'Aditivos de alta calidad para vehículos ligeros y pesados', 2, 42.59, 85, 'activo', '2025-06-12 03:03:40'),
	(35, 'PRD0035', 'Aceites Modelo 314', 'Aceites de alta calidad para vehículos ligeros y pesados', 1, 218.55, 95, 'activo', '2025-06-12 03:03:40'),
	(36, 'PRD0036', 'Lubricantes Modelo 611', 'Lubricantes de alta calidad para vehículos ligeros y pesados', 15, 229.84, 22, 'activo', '2025-06-12 03:03:40'),
	(37, 'PRD0037', 'Sensores Modelo 800', 'Sensores de alta calidad para vehículos ligeros y pesados', 19, 266.65, 80, 'activo', '2025-06-12 03:03:40'),
	(38, 'PRD0038', 'Filtros de Aceite Modelo 855', 'Filtros de Aceite de alta calidad para vehículos ligeros y pesados', 11, 28.77, 87, 'activo', '2025-06-12 03:03:40'),
	(39, 'PRD0039', 'Arrancadores Modelo 870', 'Arrancadores de alta calidad para vehículos ligeros y pesados', 5, 296.62, 5, 'activo', '2025-06-12 03:03:40'),
	(40, 'PRD0040', 'Filtros de Aceite Modelo 602', 'Filtros de Aceite de alta calidad para vehículos ligeros y pesados', 11, 73.67, 60, 'activo', '2025-06-12 03:03:40'),
	(41, 'PRD0041', 'Pastillas de Freno Modelo 715', 'Pastillas de Freno de alta calidad para vehículos ligeros y pesados', 16, 296.43, 62, 'activo', '2025-06-12 03:03:40'),
	(42, 'PRD0042', 'Filtros de Aceite Modelo 703', 'Filtros de Aceite de alta calidad para vehículos ligeros y pesados', 11, 206.73, 20, 'activo', '2025-06-12 03:03:40'),
	(43, 'PRD0043', 'Refrigerantes Modelo 178', 'Refrigerantes de alta calidad para vehículos ligeros y pesados', 18, 251.77, 98, 'activo', '2025-06-12 03:03:40'),
	(44, 'PRD0044', 'Sensores Modelo 205', 'Sensores de alta calidad para vehículos ligeros y pesados', 19, 101.69, 44, 'activo', '2025-06-12 03:03:40'),
	(45, 'PRD0045', 'Espejos Modelo 913', 'Espejos de alta calidad para vehículos ligeros y pesados', 10, 49.60, 23, 'activo', '2025-06-12 03:03:40'),
	(46, 'PRD0046', 'Bombillas Modelo 951', 'Bombillas de alta calidad para vehículos ligeros y pesados', 7, 281.78, 43, 'activo', '2025-06-12 03:03:40'),
	(47, 'PRD0047', 'Lubricantes Modelo 511', 'Lubricantes de alta calidad para vehículos ligeros y pesados', 15, 120.78, 98, 'activo', '2025-06-12 03:03:40'),
	(48, 'PRD0048', 'Limpiaparabrisas Modelo 876', 'Limpiaparabrisas de alta calidad para vehículos ligeros y pesados', 14, 139.51, 88, 'activo', '2025-06-12 03:03:40'),
	(49, 'PRD0049', 'Sensores Modelo 923', 'Sensores de alta calidad para vehículos ligeros y pesados', 19, 198.50, 30, 'activo', '2025-06-12 03:03:40'),
	(50, 'PRD0050', 'Lubricantes Modelo 414', 'Lubricantes de alta calidad para vehículos ligeros y pesados', 15, 24.82, 85, 'activo', '2025-06-12 03:03:40'),
	(51, 'PRD0051', 'Bujías Modelo 326', 'Bujías de alta calidad para vehículos ligeros y pesados', 8, 33.79, 57, 'activo', '2025-06-12 03:03:40'),
	(52, 'PRD0052', 'Alternadores Modelo 797', 'Alternadores de alta calidad para vehículos ligeros y pesados', 3, 239.82, 75, 'activo', '2025-06-12 03:03:40'),
	(53, 'PRD0053', 'Alternadores Modelo 437', 'Alternadores de alta calidad para vehículos ligeros y pesados', 3, 67.89, 22, 'activo', '2025-06-12 03:03:40'),
	(54, 'PRD0054', 'Inyectores Modelo 302', 'Inyectores de alta calidad para vehículos ligeros y pesados', 13, 201.77, 66, 'activo', '2025-06-12 03:03:40'),
	(55, 'PRD0055', 'Inyectores Modelo 892', 'Inyectores de alta calidad para vehículos ligeros y pesados', 13, 48.23, 16, 'activo', '2025-06-12 03:03:40'),
	(56, 'PRD0056', 'Refrigerantes Modelo 436', 'Refrigerantes de alta calidad para vehículos ligeros y pesados', 18, 242.33, 51, 'activo', '2025-06-12 03:03:40'),
	(57, 'PRD0057', 'Limpiaparabrisas Modelo 400', 'Limpiaparabrisas de alta calidad para vehículos ligeros y pesados', 14, 249.85, 15, 'activo', '2025-06-12 03:03:40'),
	(58, 'PRD0058', 'Baterías Modelo 200', 'Baterías de alta calidad para vehículos ligeros y pesados', 6, 113.27, 45, 'activo', '2025-06-12 03:03:40'),
	(59, 'PRD0059', 'Lubricantes Modelo 311', 'Lubricantes de alta calidad para vehículos ligeros y pesados', 15, 267.19, 98, 'activo', '2025-06-12 03:03:40'),
	(60, 'PRD0060', 'Correas Modelo 997', 'Correas de alta calidad para vehículos ligeros y pesados', 9, 194.91, 21, 'activo', '2025-06-12 03:03:40'),
	(61, 'PRD0061', 'Turbos Modelo 352', 'Turbos de alta calidad para vehículos ligeros y pesados', 20, 106.27, 54, 'activo', '2025-06-12 03:03:40'),
	(62, 'PRD0062', 'Filtros de Aceite Modelo 317', 'Filtros de Aceite de alta calidad para vehículos ligeros y pesados', 11, 280.08, 80, 'activo', '2025-06-12 03:03:40'),
	(63, 'PRD0063', 'Aditivos Modelo 657', 'Aditivos de alta calidad para vehículos ligeros y pesados', 2, 127.15, 18, 'activo', '2025-06-12 03:03:40'),
	(64, 'PRD0064', 'Correas Modelo 179', 'Correas de alta calidad para vehículos ligeros y pesados', 9, 273.26, 51, 'activo', '2025-06-12 03:03:40'),
	(65, 'PRD0065', 'Bombillas Modelo 969', 'Bombillas de alta calidad para vehículos ligeros y pesados', 7, 263.44, 33, 'activo', '2025-06-12 03:03:40'),
	(66, 'PRD0066', 'Aceites Modelo 163', 'Aceites de alta calidad para vehículos ligeros y pesados', 1, 108.00, 19, 'activo', '2025-06-12 03:03:40'),
	(67, 'PRD0067', 'Espejos Modelo 403', 'Espejos de alta calidad para vehículos ligeros y pesados', 10, 89.48, 55, 'activo', '2025-06-12 03:03:40'),
	(68, 'PRD0068', 'Baterías Modelo 230', 'Baterías de alta calidad para vehículos ligeros y pesados', 6, 192.71, 65, 'activo', '2025-06-12 03:03:40'),
	(69, 'PRD0069', 'Aceites Modelo 590', 'Aceites de alta calidad para vehículos ligeros y pesados', 1, 196.67, 65, 'activo', '2025-06-12 03:03:40'),
	(70, 'PRD0070', 'Radiadores Modelo 657', 'Radiadores de alta calidad para vehículos ligeros y pesados', 17, 22.15, 86, 'activo', '2025-06-12 03:03:40'),
	(71, 'PRD0071', 'Baterías Modelo 297', 'Baterías de alta calidad para vehículos ligeros y pesados', 6, 192.28, 97, 'activo', '2025-06-12 03:03:40'),
	(72, 'PRD0072', 'Baterías Modelo 502', 'Baterías de alta calidad para vehículos ligeros y pesados', 6, 260.32, 32, 'activo', '2025-06-12 03:03:40'),
	(73, 'PRD0073', 'Refrigerantes Modelo 939', 'Refrigerantes de alta calidad para vehículos ligeros y pesados', 18, 187.17, 22, 'activo', '2025-06-12 03:03:41'),
	(74, 'PRD0074', 'Bujías Modelo 617', 'Bujías de alta calidad para vehículos ligeros y pesados', 8, 79.64, 14, 'activo', '2025-06-12 03:03:41'),
	(75, 'PRD0075', 'Turbos Modelo 313', 'Turbos de alta calidad para vehículos ligeros y pesados', 20, 232.26, 96, 'activo', '2025-06-12 03:03:41'),
	(76, 'PRD0076', 'Baterías Modelo 886', 'Baterías de alta calidad para vehículos ligeros y pesados', 6, 51.22, 9, 'activo', '2025-06-12 03:03:41'),
	(77, 'PRD0077', 'Amortiguadores Modelo 603', 'Amortiguadores de alta calidad para vehículos ligeros y pesados', 4, 99.80, 11, 'activo', '2025-06-12 03:03:41'),
	(78, 'PRD0078', 'Amortiguadores Modelo 498', 'Amortiguadores de alta calidad para vehículos ligeros y pesados', 4, 286.46, 7, 'activo', '2025-06-12 03:03:41'),
	(79, 'PRD0079', 'Refrigerantes Modelo 390', 'Refrigerantes de alta calidad para vehículos ligeros y pesados', 18, 85.25, 50, 'activo', '2025-06-12 03:03:41'),
	(80, 'PRD0080', 'Aditivos Modelo 718', 'Aditivos de alta calidad para vehículos ligeros y pesados', 2, 268.16, 99, 'activo', '2025-06-12 03:03:41'),
	(81, 'PRD0081', 'Amortiguadores Modelo 350', 'Amortiguadores de alta calidad para vehículos ligeros y pesados', 4, 253.04, 19, 'activo', '2025-06-12 03:03:41'),
	(82, 'PRD0082', 'Lubricantes Modelo 743', 'Lubricantes de alta calidad para vehículos ligeros y pesados', 15, 11.03, 20, 'activo', '2025-06-12 03:03:41'),
	(83, 'PRD0083', 'Baterías Modelo 341', 'Baterías de alta calidad para vehículos ligeros y pesados', 6, 294.69, 72, 'activo', '2025-06-12 03:03:41'),
	(84, 'PRD0084', 'Alternadores Modelo 141', 'Alternadores de alta calidad para vehículos ligeros y pesados', 3, 54.33, 43, 'activo', '2025-06-12 03:03:41'),
	(85, 'PRD0085', 'Turbos Modelo 500', 'Turbos de alta calidad para vehículos ligeros y pesados', 20, 80.12, 48, 'activo', '2025-06-12 03:03:41'),
	(86, 'PRD0086', 'Bombillas Modelo 990', 'Bombillas de alta calidad para vehículos ligeros y pesados', 7, 51.19, 85, 'activo', '2025-06-12 03:03:41'),
	(87, 'PRD0087', 'Radiadores Modelo 803', 'Radiadores de alta calidad para vehículos ligeros y pesados', 17, 211.31, 97, 'activo', '2025-06-12 03:03:41'),
	(88, 'PRD0088', 'Lubricantes Modelo 260', 'Lubricantes de alta calidad para vehículos ligeros y pesados', 15, 96.16, 62, 'activo', '2025-06-12 03:03:41'),
	(89, 'PRD0089', 'Radiadores Modelo 322', 'Radiadores de alta calidad para vehículos ligeros y pesados', 17, 203.51, 50, 'activo', '2025-06-12 03:03:41'),
	(90, 'PRD0090', 'Turbos Modelo 477', 'Turbos de alta calidad para vehículos ligeros y pesados', 20, 118.38, 44, 'activo', '2025-06-12 03:03:41'),
	(91, 'PRD0091', 'Aditivos Modelo 142', 'Aditivos de alta calidad para vehículos ligeros y pesados', 2, 34.04, 90, 'activo', '2025-06-12 03:03:41'),
	(92, 'PRD0092', 'Sensores Modelo 854', 'Sensores de alta calidad para vehículos ligeros y pesados', 19, 147.28, 86, 'activo', '2025-06-12 03:03:41'),
	(93, 'PRD0093', 'Filtros de Aire Modelo 992', 'Filtros de Aire de alta calidad para vehículos ligeros y pesados', 12, 132.57, 14, 'activo', '2025-06-12 03:03:41'),
	(94, 'PRD0094', 'Bujías Modelo 490', 'Bujías de alta calidad para vehículos ligeros y pesados', 8, 99.91, 61, 'activo', '2025-06-12 03:03:41'),
	(95, 'PRD0095', 'Aceites Modelo 278', 'Aceites de alta calidad para vehículos ligeros y pesados', 1, 194.56, 13, 'activo', '2025-06-12 03:03:41'),
	(96, 'PRD0096', 'Turbos Modelo 223', 'Turbos de alta calidad para vehículos ligeros y pesados', 20, 15.26, 65, 'activo', '2025-06-12 03:03:41'),
	(97, 'PRD0097', 'Radiadores Modelo 766', 'Radiadores de alta calidad para vehículos ligeros y pesados', 17, 21.82, 49, 'activo', '2025-06-12 03:03:41'),
	(98, 'PRD0098', 'Espejos Modelo 533', 'Espejos de alta calidad para vehículos ligeros y pesados', 10, 188.86, 40, 'activo', '2025-06-12 03:03:41'),
	(99, 'PRD0099', 'Sensores Modelo 480', 'Sensores de alta calidad para vehículos ligeros y pesados', 19, 132.84, 9, 'activo', '2025-06-12 03:03:41'),
	(100, 'PRD0100', 'Correas Modelo 499', 'Correas de alta calidad para vehículos ligeros y pesados', 9, 181.28, 15, 'activo', '2025-06-12 03:03:41'),
	(101, 'uuu', 'qqqqx', 'eeeeeee', 1, 45.00, 6, 'activo', '2025-06-12 03:04:37'),
	(102, 'qwerw', 'werqwerqwr', 'LLLLLLLLLLLL', 6, 10.20, 12, 'activo', '2025-06-12 04:18:20'),
	(103, '103', 'KKKKKKK', 'OOOOO', 4, 1.80, 10, 'activo', '2025-06-13 01:03:23');

-- Volcando estructura para tabla facturacion.roles
CREATE TABLE IF NOT EXISTS `roles` (
  `id_rol` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Identificador único del rol',
  `nombre_rol` varchar(50) NOT NULL COMMENT 'Nombre del rol (admin, facturador, etc.)',
  `descripcion` varchar(255) DEFAULT NULL COMMENT 'Descripción del rol',
  `estado` enum('activo','inactivo') NOT NULL DEFAULT 'activo' COMMENT 'Estado del rol',
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp() COMMENT 'Fecha de creación del rol',
  PRIMARY KEY (`id_rol`) USING BTREE,
  UNIQUE KEY `nombre_rol` (`nombre_rol`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla facturacion.roles: ~3 rows (aproximadamente)
INSERT INTO `roles` (`id_rol`, `nombre_rol`, `descripcion`, `estado`, `fecha_creacion`) VALUES
	(1, 'admin', 'Administrador del sistema', 'activo', '2025-06-10 02:08:17'),
	(2, 'facturador', 'Usuario encargado de emitir facturas', 'activo', '2025-06-10 02:08:17'),
	(3, 'auditor', 'Usuario con acceso a reportes y auditoría', 'activo', '2025-06-10 02:08:17');

-- Volcando estructura para tabla facturacion.usuarios
CREATE TABLE IF NOT EXISTS `usuarios` (
  `id_usuario` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Identificador único del usuario',
  `nombre` varchar(100) NOT NULL COMMENT 'Nombre completo del usuario',
  `correo` varchar(150) NOT NULL COMMENT 'Correo electrónico del usuario, debe ser único',
  `contraseña` varchar(256) NOT NULL COMMENT 'Hash de la contraseña del usuario',
  `id_rol` tinyint(4) unsigned NOT NULL COMMENT 'Relacionado roles',
  `estado` enum('activo','inactivo','suspendido') DEFAULT 'activo' COMMENT 'Estado actual del usuario',
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp() COMMENT 'Fecha de creación del usuario',
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp() COMMENT 'Fecha en que se registró el dato',
  `estado_registro` enum('activo','inactivo','eliminado') DEFAULT 'activo' COMMENT 'Estado lógico del registro',
  `fecha_modificacion` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp() COMMENT 'Última fecha de modificación',
  `usuario_creacion` int(11) unsigned DEFAULT NULL COMMENT 'ID del usuario que creó el registro',
  `usuario_modificacion` int(11) unsigned DEFAULT NULL COMMENT 'ID del usuario que modificó el registro',
  `origen_dato` varchar(100) DEFAULT NULL COMMENT 'Origen del dato (API, módulo, etc.)',
  PRIMARY KEY (`id_usuario`),
  UNIQUE KEY `correo` (`correo`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla facturacion.usuarios: ~1 rows (aproximadamente)
INSERT INTO `usuarios` (`id_usuario`, `nombre`, `correo`, `contraseña`, `id_rol`, `estado`, `fecha_creacion`, `fecha_registro`, `estado_registro`, `fecha_modificacion`, `usuario_creacion`, `usuario_modificacion`, `origen_dato`) VALUES
	(1, 'ADMINISTRADOR', 'acolumba@gmail.com', '$2y$10$nDP1KpC2gsUDNS8x4xV.3.dSju3dqeBnukhzghdDepCinfQgvGpBu', 1, 'activo', '2025-06-10 02:05:25', '2025-06-10 02:05:25', 'activo', '2025-06-14 23:53:16', NULL, 1, NULL);

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
