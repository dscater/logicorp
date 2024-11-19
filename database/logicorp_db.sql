-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost:3306
-- Tiempo de generación: 19-11-2024 a las 19:32:08
-- Versión del servidor: 8.0.30
-- Versión de PHP: 8.2.22

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `logicorp_db`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `asignacions`
--

CREATE TABLE `asignacions` (
  `id` bigint UNSIGNED NOT NULL,
  `contrato_id` bigint UNSIGNED NOT NULL,
  `fecha_registro` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `asignacion_detalles`
--

CREATE TABLE `asignacion_detalles` (
  `id` bigint UNSIGNED NOT NULL,
  `empresa_id` bigint UNSIGNED NOT NULL,
  `p_adjudicacion` double NOT NULL,
  `cantidad` double NOT NULL,
  `cantidad_entero` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `conductors`
--

CREATE TABLE `conductors` (
  `id` bigint UNSIGNED NOT NULL,
  `nombre` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `paterno` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `materno` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ci` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ci_exp` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nacionalidad` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `fecha_nac` date NOT NULL,
  `sexo` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `estado_civil` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nro_licencia` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `categoria` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `fecha_emision` date DEFAULT NULL,
  `fecha_vencimiento` date NOT NULL,
  `fono` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `foto` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `observacion` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fecha_registro` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `conductors`
--

INSERT INTO `conductors` (`id`, `nombre`, `paterno`, `materno`, `ci`, `ci_exp`, `nacionalidad`, `fecha_nac`, `sexo`, `estado_civil`, `nro_licencia`, `categoria`, `fecha_emision`, `fecha_vencimiento`, `fono`, `foto`, `observacion`, `fecha_registro`, `created_at`, `updated_at`) VALUES
(1, 'PEDRO', 'MARTINEZ', 'MAMANI', '3333333', 'LP', 'BOLIVIANO', '1991-01-01', 'MASCULINO', 'SOLTERO', '3333333', 'C', '2023-01-01', '2027-03-03', '77777777', '1732034697_1.jpg', 'OBSERVACION CONDUCTOR', '2024-11-19', '2024-11-19 20:44:57', '2024-11-19 20:46:58');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `configuracions`
--

CREATE TABLE `configuracions` (
  `id` bigint UNSIGNED NOT NULL,
  `nombre_sistema` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `alias` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `razon_social` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ciudad` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `dir` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `fono` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `correo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `web` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `actividad` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `logo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `configuracions`
--

INSERT INTO `configuracions` (`id`, `nombre_sistema`, `alias`, `razon_social`, `ciudad`, `dir`, `fono`, `correo`, `web`, `actividad`, `logo`, `created_at`, `updated_at`) VALUES
(1, 'LOGICORP', 'LC', 'LOGICORP S.A.', 'LA PAZ', 'ZONA LOS OLIVOS', '77777777', 'LOGICORP@GMAIL.COM', 'LOGICORP.COM', 'ACTIVIDAD', '1725897866_1.jpg', NULL, '2024-11-11 19:58:10');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `contratos`
--

CREATE TABLE `contratos` (
  `id` bigint UNSIGNED NOT NULL,
  `codigo` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nro_lote` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `empresa_id` bigint UNSIGNED NOT NULL,
  `p_asignado` double NOT NULL,
  `fecha_registro` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `contrato_detalles`
--

CREATE TABLE `contrato_detalles` (
  `id` bigint UNSIGNED NOT NULL,
  `contrato_id` bigint UNSIGNED NOT NULL,
  `proveedor_id` bigint UNSIGNED NOT NULL,
  `producto_id` bigint UNSIGNED NOT NULL,
  `tramo` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `frontera` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cantidad` double NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `empresas`
--

CREATE TABLE `empresas` (
  `id` bigint UNSIGNED NOT NULL,
  `razon_social` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nit` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nom_representante` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ap_representante` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `fono` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `correo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `descripcion` varchar(600) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tipo` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `fecha_registro` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `empresas`
--

INSERT INTO `empresas` (`id`, `razon_social`, `nit`, `nom_representante`, `ap_representante`, `fono`, `correo`, `descripcion`, `tipo`, `fecha_registro`, `created_at`, `updated_at`) VALUES
(1, 'EMPRESA 1', '111111122', 'JUAN', 'PERES MAMANI', '6666666', 'EMPRESA1@GMAIL.COM', 'DESC. EMPRESA 1', 'EMPRESA', '2024-11-19', '2024-11-19 20:03:36', '2024-11-19 20:03:47'),
(2, 'SOCIEDAD 1', '222233333', 'FERNANDO', 'PAREDES CHOQUE', '77766666', 'SOCIEDAD1@GMAIL.COM', '', 'ASOCIACIÓN', '2024-11-19', '2024-11-19 20:05:29', '2024-11-19 20:05:29');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `historial_accions`
--

CREATE TABLE `historial_accions` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `accion` varchar(155) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descripcion` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `datos_original` text COLLATE utf8mb4_unicode_ci,
  `datos_nuevo` text COLLATE utf8mb4_unicode_ci,
  `modulo` varchar(155) COLLATE utf8mb4_unicode_ci NOT NULL,
  `fecha` date NOT NULL,
  `hora` time NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `historial_accions`
--

INSERT INTO `historial_accions` (`id`, `user_id`, `accion`, `descripcion`, `datos_original`, `datos_nuevo`, `modulo`, `fecha`, `hora`, `created_at`, `updated_at`) VALUES
(1, 1, 'CREACIÓN', 'EL USUARIO admin REGISTRO UN USUARIO', 'id: 2<br/>usuario: JPERES<br/>nombre: JUAN<br/>paterno: PERES<br/>materno: MAMANI<br/>ci: 1111<br/>ci_exp: LP<br/>dir: LOS OLIVOS<br/>email: JUAN@GMAIL.COM<br/>fono: 7777777<br/>password: $2y$12$ej1afafysGqKe.LEHxxzXedpL6h7lR3MiSqw32JiOC9Ol5CY30P7y<br/>tipo: GERENTE<br/>foto: 1731340717_JPERES.jpg<br/>fecha_registro: 2024-11-11<br/>acceso: 1<br/>created_at: 2024-11-11 15:58:37<br/>updated_at: 2024-11-11 15:58:37<br/>', NULL, 'USUARIOS', '2024-11-11', '15:58:37', '2024-11-11 19:58:37', '2024-11-11 19:58:37'),
(2, 1, 'CREACIÓN', 'EL USUARIO admin REGISTRO UN PROVEEDOR', 'id: 1<br/>razon_social: PROVEEDOR 1<br/>descripcion: DESC. 1<br/>fecha_registro: <br/>created_at: 2024-11-19 15:44:13<br/>updated_at: 2024-11-19 15:44:13<br/>', NULL, 'PROVEEDORES', '2024-11-19', '15:44:13', '2024-11-19 19:44:13', '2024-11-19 19:44:13'),
(3, 1, 'CREACIÓN', 'EL USUARIO admin REGISTRO UN PROVEEDOR', 'id: 2<br/>razon_social: ASD<br/>descripcion: ASD<br/>fecha_registro: <br/>created_at: 2024-11-19 15:44:47<br/>updated_at: 2024-11-19 15:44:47<br/>', NULL, 'PROVEEDORES', '2024-11-19', '15:44:47', '2024-11-19 19:44:47', '2024-11-19 19:44:47'),
(4, 1, 'MODIFICACIÓN', 'EL USUARIO admin MODIFICÓ UN PROVEEDOR', 'id: 2<br/>razon_social: ASD<br/>descripcion: ASD<br/>fecha_registro: <br/>created_at: 2024-11-19 15:44:47<br/>updated_at: 2024-11-19 15:44:47<br/>', 'id: 2<br/>razon_social: EEEE<br/>descripcion: FFF<br/>fecha_registro: <br/>created_at: 2024-11-19 15:44:47<br/>updated_at: 2024-11-19 15:44:53<br/>', 'PROVEEDORES', '2024-11-19', '15:44:53', '2024-11-19 19:44:53', '2024-11-19 19:44:53'),
(5, 1, 'ELIMINACIÓN', 'EL USUARIO admin ELIMINÓ UN PROVEEDOR', 'id: 2<br/>razon_social: EEEE<br/>descripcion: FFF<br/>fecha_registro: <br/>created_at: 2024-11-19 15:44:47<br/>updated_at: 2024-11-19 15:44:53<br/>', NULL, 'PROVEEDORES', '2024-11-19', '15:45:32', '2024-11-19 19:45:32', '2024-11-19 19:45:32'),
(6, 1, 'CREACIÓN', 'EL USUARIO admin REGISTRO UN PROVEEDOR', 'id: 2<br/>razon_social: PROVEEDOR 2<br/>descripcion: <br/>fecha_registro: <br/>created_at: 2024-11-19 15:46:05<br/>updated_at: 2024-11-19 15:46:05<br/>', NULL, 'PROVEEDORES', '2024-11-19', '15:46:05', '2024-11-19 19:46:05', '2024-11-19 19:46:05'),
(7, 1, 'CREACIÓN', 'EL USUARIO admin REGISTRO UN PROVEEDOR', 'id: 3<br/>razon_social: PROVEEDOR 3<br/>descripcion: <br/>fecha_registro: 2024-11-19<br/>created_at: 2024-11-19 15:46:47<br/>updated_at: 2024-11-19 15:46:47<br/>', NULL, 'PROVEEDORES', '2024-11-19', '15:46:47', '2024-11-19 19:46:47', '2024-11-19 19:46:47'),
(8, 1, 'CREACIÓN', 'EL USUARIO admin REGISTRO UNA EMPRESA/SOCIEDAD', 'id: 1<br/>razon_social: EMPREAS 1<br/>nit: 1111111<br/>nom_representante: JUAN<br/>ap_representante: PERES<br/>fono: 6666666<br/>correo: EMPRESA1@GMAIL.COM<br/>descripcion: DESC. EMPRESA 1<br/>tipo: EMPRESA<br/>fecha_registro: 2024-11-19<br/>created_at: 2024-11-19 16:03:36<br/>updated_at: 2024-11-19 16:03:36<br/>', NULL, 'EMPRESA/SOCIEDAD', '2024-11-19', '16:03:36', '2024-11-19 20:03:36', '2024-11-19 20:03:36'),
(9, 1, 'MODIFICACIÓN', 'EL USUARIO admin MODIFICÓ UNA EMPRESA/SOCIEDAD', 'id: 1<br/>razon_social: EMPREAS 1<br/>nit: 1111111<br/>nom_representante: JUAN<br/>ap_representante: PERES<br/>fono: 6666666<br/>correo: EMPRESA1@GMAIL.COM<br/>descripcion: DESC. EMPRESA 1<br/>tipo: EMPRESA<br/>fecha_registro: 2024-11-19<br/>created_at: 2024-11-19 16:03:36<br/>updated_at: 2024-11-19 16:03:36<br/>', 'id: 1<br/>razon_social: EMPRESA 1<br/>nit: 111111122<br/>nom_representante: JUAN<br/>ap_representante: PERES MAMANI<br/>fono: 6666666<br/>correo: EMPRESA1@GMAIL.COM<br/>descripcion: DESC. EMPRESA 1<br/>tipo: EMPRESA<br/>fecha_registro: 2024-11-19<br/>created_at: 2024-11-19 16:03:36<br/>updated_at: 2024-11-19 16:03:47<br/>', 'EMPRESA/SOCIEDAD', '2024-11-19', '16:03:47', '2024-11-19 20:03:47', '2024-11-19 20:03:47'),
(10, 1, 'CREACIÓN', 'EL USUARIO admin REGISTRO UNA EMPRESA/SOCIEDAD', 'id: 2<br/>razon_social: SOCIEDAD 1<br/>nit: 222111<br/>nom_representante: FERNANDO<br/>ap_representante: PAREDES<br/>fono: 77777777<br/>correo: <br/>descripcion: <br/>tipo: ASOCIACIÓN<br/>fecha_registro: 2024-11-19<br/>created_at: 2024-11-19 16:04:13<br/>updated_at: 2024-11-19 16:04:13<br/>', NULL, 'EMPRESA/SOCIEDAD', '2024-11-19', '16:04:13', '2024-11-19 20:04:13', '2024-11-19 20:04:13'),
(11, 1, 'ELIMINACIÓN', 'EL USUARIO admin ELIMINÓ UNA EMPRESA/SOCIEDAD', 'id: 2<br/>razon_social: SOCIEDAD 1<br/>nit: 222111<br/>nom_representante: FERNANDO<br/>ap_representante: PAREDES<br/>fono: 77777777<br/>correo: <br/>descripcion: <br/>tipo: ASOCIACIÓN<br/>fecha_registro: 2024-11-19<br/>created_at: 2024-11-19 16:04:13<br/>updated_at: 2024-11-19 16:04:13<br/>', NULL, 'EMPRESA/SOCIEDAD', '2024-11-19', '16:04:17', '2024-11-19 20:04:17', '2024-11-19 20:04:17'),
(12, 1, 'CREACIÓN', 'EL USUARIO admin REGISTRO UNA EMPRESA/SOCIEDAD', 'id: 2<br/>razon_social: SOCIEDAD 1<br/>nit: 222233333<br/>nom_representante: FERNANDO<br/>ap_representante: PAREDES CHOQUE<br/>fono: 77766666<br/>correo: SOCIEDAD1@GMAIL.COM<br/>descripcion: <br/>tipo: ASOCIACIÓN<br/>fecha_registro: 2024-11-19<br/>created_at: 2024-11-19 16:05:29<br/>updated_at: 2024-11-19 16:05:29<br/>', NULL, 'EMPRESA/SOCIEDAD', '2024-11-19', '16:05:29', '2024-11-19 20:05:29', '2024-11-19 20:05:29'),
(13, 1, 'CREACIÓN', 'EL USUARIO admin REGISTRO UN CONDUCTOR', 'id: 1<br/>nombre: PEDRO<br/>paterno: MARTINEZ<br/>materno: MAMANI<br/>ci: 3333333<br/>ci_exp: LP<br/>nacionalidad: BOLIVIANO<br/>fecha_nac: 1990-01-01<br/>sexo: MASCULINO<br/>estado_civil: SOLTERO<br/>nro_licencia: 3333333<br/>categoria: C<br/>fecha_emision: 2023-01-01<br/>fecha_vencimiento: 2026-03-03<br/>fono: 77777777<br/>foto: 1732034697_1.jpg<br/>observacion: OBSERVACION CONDUCTOR<br/>fecha_registro: 2024-11-19<br/>created_at: 2024-11-19 16:44:57<br/>updated_at: 2024-11-19 16:44:57<br/>', NULL, 'CONDUCTORES', '2024-11-19', '16:44:57', '2024-11-19 20:44:57', '2024-11-19 20:44:57'),
(14, 1, 'MODIFICACIÓN', 'EL USUARIO admin MODIFICÓ UN CONDUCTOR', 'id: 1<br/>nombre: PEDRO<br/>paterno: MARTINEZ<br/>materno: MAMANI<br/>ci: 3333333<br/>ci_exp: LP<br/>nacionalidad: BOLIVIANO<br/>fecha_nac: 1990-01-01<br/>sexo: MASCULINO<br/>estado_civil: SOLTERO<br/>nro_licencia: 3333333<br/>categoria: C<br/>fecha_emision: 2023-01-01<br/>fecha_vencimiento: 2026-03-03<br/>fono: 77777777<br/>foto: 1732034697_1.jpg<br/>observacion: OBSERVACION CONDUCTOR<br/>fecha_registro: 2024-11-19<br/>created_at: 2024-11-19 16:44:57<br/>updated_at: 2024-11-19 16:44:57<br/>', 'id: 1<br/>nombre: PEDROS<br/>paterno: MARTINEZS<br/>materno: MAMANIS<br/>ci: 33333331<br/>ci_exp: CB<br/>nacionalidad: BOLIVIANOS<br/>fecha_nac: 1991-01-01<br/>sexo: MASCULINOS<br/>estado_civil: SOLTEROS<br/>nro_licencia: 33333331<br/>categoria: C<br/>fecha_emision: 2023-01-01<br/>fecha_vencimiento: 2027-03-03<br/>fono: 777777771<br/>foto: 1732034697_1.jpg<br/>observacion: OBSERVACION CONDUCTORS<br/>fecha_registro: 2024-11-19<br/>created_at: 2024-11-19 16:44:57<br/>updated_at: 2024-11-19 16:45:21<br/>', 'CONDUCTORES', '2024-11-19', '16:45:21', '2024-11-19 20:45:21', '2024-11-19 20:45:21'),
(15, 1, 'MODIFICACIÓN', 'EL USUARIO admin MODIFICÓ UN CONDUCTOR', 'id: 1<br/>nombre: PEDROS<br/>paterno: MARTINEZS<br/>materno: MAMANIS<br/>ci: 33333331<br/>ci_exp: CB<br/>nacionalidad: BOLIVIANOS<br/>fecha_nac: 1991-01-01<br/>sexo: MASCULINOS<br/>estado_civil: SOLTEROS<br/>nro_licencia: 33333331<br/>categoria: C<br/>fecha_emision: 2023-01-01<br/>fecha_vencimiento: 2027-03-03<br/>fono: 777777771<br/>foto: 1732034697_1.jpg<br/>observacion: OBSERVACION CONDUCTORS<br/>fecha_registro: 2024-11-19<br/>created_at: 2024-11-19 16:44:57<br/>updated_at: 2024-11-19 16:45:21<br/>', 'id: 1<br/>nombre: PEDRO<br/>paterno: MARTINEZ<br/>materno: MAMANI<br/>ci: 3333333<br/>ci_exp: LP<br/>nacionalidad: BOLIVIANO<br/>fecha_nac: 1991-01-01<br/>sexo: MASCULINO<br/>estado_civil: SOLTERO<br/>nro_licencia: 3333333<br/>categoria: C<br/>fecha_emision: 2023-01-01<br/>fecha_vencimiento: 2027-03-03<br/>fono: 77777777<br/>foto: 1732034697_1.jpg<br/>observacion: OBSERVACION CONDUCTOR<br/>fecha_registro: 2024-11-19<br/>created_at: 2024-11-19 16:44:57<br/>updated_at: 2024-11-19 16:46:58<br/>', 'CONDUCTORES', '2024-11-19', '16:46:58', '2024-11-19 20:46:58', '2024-11-19 20:46:58'),
(16, 1, 'CREACIÓN', 'EL USUARIO admin REGISTRO UN CONDUCTOR', 'id: 2<br/>nombre: ASD<br/>paterno: ASD<br/>materno: <br/>ci: 123213<br/>ci_exp: CB<br/>nacionalidad: ASD<br/>fecha_nac: 1990-01-01<br/>sexo: ASD<br/>estado_civil: QWEQWE<br/>nro_licencia: 123123<br/>categoria: D<br/>fecha_emision: <br/>fecha_vencimiento: 2024-03-03<br/>fono: 44444<br/>foto: <br/>observacion: <br/>fecha_registro: 2024-11-19<br/>created_at: 2024-11-19 16:50:35<br/>updated_at: 2024-11-19 16:50:35<br/>', NULL, 'CONDUCTORES', '2024-11-19', '16:50:35', '2024-11-19 20:50:35', '2024-11-19 20:50:35'),
(17, 1, 'MODIFICACIÓN', 'EL USUARIO admin MODIFICÓ UN CONDUCTOR', 'id: 2<br/>nombre: ASD<br/>paterno: ASD<br/>materno: <br/>ci: 123213<br/>ci_exp: CB<br/>nacionalidad: ASD<br/>fecha_nac: 1990-01-01<br/>sexo: ASD<br/>estado_civil: QWEQWE<br/>nro_licencia: 123123<br/>categoria: D<br/>fecha_emision: <br/>fecha_vencimiento: 2024-03-03<br/>fono: 44444<br/>foto: <br/>observacion: <br/>fecha_registro: 2024-11-19<br/>created_at: 2024-11-19 16:50:35<br/>updated_at: 2024-11-19 16:50:35<br/>', 'id: 2<br/>nombre: ASD<br/>paterno: ASD<br/>materno: <br/>ci: 123213<br/>ci_exp: CB<br/>nacionalidad: ASD<br/>fecha_nac: 1990-01-01<br/>sexo: ASD<br/>estado_civil: QWEQWE<br/>nro_licencia: 123123<br/>categoria: D<br/>fecha_emision: <br/>fecha_vencimiento: 2024-03-03<br/>fono: 44444<br/>foto: <br/>observacion: <br/>fecha_registro: 2024-11-19<br/>created_at: 2024-11-19 16:50:35<br/>updated_at: 2024-11-19 16:50:35<br/>', 'CONDUCTORES', '2024-11-19', '16:50:43', '2024-11-19 20:50:43', '2024-11-19 20:50:43'),
(18, 1, 'ELIMINACIÓN', 'EL USUARIO admin ELIMINÓ UN CONDUCTOR', 'id: 2<br/>nombre: ASD<br/>paterno: ASD<br/>materno: <br/>ci: 123213<br/>ci_exp: CB<br/>nacionalidad: ASD<br/>fecha_nac: 1990-01-01<br/>sexo: ASD<br/>estado_civil: QWEQWE<br/>nro_licencia: 123123<br/>categoria: D<br/>fecha_emision: <br/>fecha_vencimiento: 2024-03-03<br/>fono: 44444<br/>foto: <br/>observacion: <br/>fecha_registro: 2024-11-19<br/>created_at: 2024-11-19 16:50:35<br/>updated_at: 2024-11-19 16:50:35<br/>', NULL, 'CONDUCTORES', '2024-11-19', '16:50:59', '2024-11-19 20:50:59', '2024-11-19 20:50:59');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '2024_01_31_165641_create_configuracions_table', 1),
(3, '2024_02_02_205431_create_historial_accions_table', 1),
(4, '2024_11_11_152144_create_proveedors_table', 2),
(5, '2024_11_11_152152_create_empresas_table', 2),
(6, '2024_11_11_152204_create_conductors_table', 2),
(7, '2024_11_11_152205_create_vehiculos_table', 2),
(8, '2024_11_11_152301_create_productos_table', 2),
(9, '2024_11_11_152315_create_contratos_table', 2),
(10, '2024_11_11_152327_create_contrato_detalles_table', 2),
(11, '2024_11_11_152338_create_asignacions_table', 2),
(12, '2024_11_11_152404_create_asignacion_detalles_table', 2),
(13, '2024_11_11_152414_create_programacions_table', 2),
(14, '2024_11_11_152429_create_viajes_table', 3),
(15, '2024_11_11_152454_create_pagos_table', 3);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pagos`
--

CREATE TABLE `pagos` (
  `id` bigint UNSIGNED NOT NULL,
  `programacion_id` bigint UNSIGNED NOT NULL,
  `mes_anio` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cto` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `fecha` date DEFAULT NULL,
  `retencion` decimal(24,2) NOT NULL,
  `desc_merma` decimal(24,2) NOT NULL,
  `total_pagado` decimal(24,2) NOT NULL,
  `fecha_registro` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `id` bigint UNSIGNED NOT NULL,
  `nombre` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descripcion` varchar(600) COLLATE utf8mb4_unicode_ci NOT NULL,
  `fecha_registro` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `programacions`
--

CREATE TABLE `programacions` (
  `id` bigint UNSIGNED NOT NULL,
  `contrato_id` bigint UNSIGNED NOT NULL,
  `empresa_id` bigint UNSIGNED NOT NULL,
  `asociacion_id` bigint UNSIGNED NOT NULL,
  `producto_id` bigint UNSIGNED NOT NULL,
  `proveedor_id` bigint UNSIGNED NOT NULL,
  `vehiculo_id` bigint UNSIGNED NOT NULL,
  `conductor_id` bigint UNSIGNED NOT NULL,
  `origen_destino` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `frontera` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `fecha_programacion` date NOT NULL,
  `descripcion` varchar(600) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fecha_registro` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `proveedors`
--

CREATE TABLE `proveedors` (
  `id` bigint UNSIGNED NOT NULL,
  `razon_social` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descripcion` varchar(600) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fecha_registro` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `proveedors`
--

INSERT INTO `proveedors` (`id`, `razon_social`, `descripcion`, `fecha_registro`, `created_at`, `updated_at`) VALUES
(1, 'PROVEEDOR 1', 'DESC. 1', '2024-11-19', '2024-11-19 19:44:13', '2024-11-19 19:44:13'),
(2, 'PROVEEDOR 2', '', '2024-11-19', '2024-11-19 19:46:05', '2024-11-19 19:46:05'),
(3, 'PROVEEDOR 3', '', '2024-11-19', '2024-11-19 19:46:47', '2024-11-19 19:46:47');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `usuario` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nombre` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `paterno` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `materno` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ci` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ci_exp` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `dir` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fono` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tipo` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `foto` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fecha_registro` date NOT NULL,
  `acceso` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id`, `usuario`, `nombre`, `paterno`, `materno`, `ci`, `ci_exp`, `dir`, `email`, `fono`, `password`, `tipo`, `foto`, `fecha_registro`, `acceso`, `created_at`, `updated_at`) VALUES
(1, 'admin', 'admin', 'admin', NULL, '0', '', '', 'admin@admin.com', '', '$2y$12$65d4fgZsvBV5Lc/AxNKh4eoUdbGyaczQ4sSco20feSQANshNLuxSC', 'ADMINISTRADOR', NULL, '2024-11-09', 1, NULL, NULL),
(2, 'JPERES', 'JUAN', 'PERES', 'MAMANI', '1111', 'LP', 'LOS OLIVOS', 'JUAN@GMAIL.COM', '7777777', '$2y$12$ej1afafysGqKe.LEHxxzXedpL6h7lR3MiSqw32JiOC9Ol5CY30P7y', 'GERENTE', '1731340717_JPERES.jpg', '2024-11-11', 1, '2024-11-11 19:58:37', '2024-11-11 19:58:37');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `vehiculos`
--

CREATE TABLE `vehiculos` (
  `id` bigint UNSIGNED NOT NULL,
  `marca` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `modelo` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `anio` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `placa` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nro_chasis` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `color` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `foto` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `descripcion` varchar(600) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nro_bin` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nro_cha_tanque` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `marca_tanque` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `capacidad_tanque` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nro_comportamiento` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `volumen_tanque` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ejes_tanque` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nro_precientos` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tipo_tanque` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `conductor_id` bigint UNSIGNED NOT NULL,
  `observacion` varchar(600) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fecha_registro` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `viajes`
--

CREATE TABLE `viajes` (
  `id` bigint UNSIGNED NOT NULL,
  `programacion_id` bigint UNSIGNED NOT NULL,
  `volumen_programado` double NOT NULL,
  `tramo` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nomina` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `resolucion` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `dim` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `estado` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `observaciones` varchar(600) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fecha_carga` date DEFAULT NULL,
  `volumen_cargado` double DEFAULT NULL,
  `total` double DEFAULT NULL,
  `cre_carga` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `volumen_recepcionado` double DEFAULT NULL,
  `total2` double DEFAULT NULL,
  `mermas` double DEFAULT NULL,
  `dif_litros` double DEFAULT NULL,
  `merma_ypfb` double DEFAULT NULL,
  `merma_cobrar` double DEFAULT NULL,
  `volumen_facturar` double DEFAULT NULL,
  `fecha_descarga` date DEFAULT NULL,
  `segun_cre` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `factura_lote` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `atq_lapaz` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mes_servicio` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `dim2` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `crt` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `vol_crtm3` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `peso_crt` double DEFAULT NULL,
  `planta_carga_crt` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fecha_cruce_frontera` date DEFAULT NULL,
  `mic_dta` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `vol_mic` double DEFAULT NULL,
  `peso_mic` double DEFAULT NULL,
  `parte_recepcion` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `vol_parte_mic` double DEFAULT NULL,
  `vol_parte_lts` double DEFAULT NULL,
  `peso_parte` double DEFAULT NULL,
  `observaciones2` text COLLATE utf8mb4_unicode_ci,
  `nro_fac_albodab` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fecha_factura` date DEFAULT NULL,
  `importe_bs` decimal(24,2) DEFAULT NULL,
  `observaciones3` text COLLATE utf8mb4_unicode_ci,
  `observaciones_general` text COLLATE utf8mb4_unicode_ci,
  `fecha_registro` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `asignacions`
--
ALTER TABLE `asignacions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `asignacions_contrato_id_foreign` (`contrato_id`);

--
-- Indices de la tabla `asignacion_detalles`
--
ALTER TABLE `asignacion_detalles`
  ADD PRIMARY KEY (`id`),
  ADD KEY `asignacion_detalles_empresa_id_foreign` (`empresa_id`);

--
-- Indices de la tabla `conductors`
--
ALTER TABLE `conductors`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `configuracions`
--
ALTER TABLE `configuracions`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `contratos`
--
ALTER TABLE `contratos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `contratos_codigo_unique` (`codigo`);

--
-- Indices de la tabla `contrato_detalles`
--
ALTER TABLE `contrato_detalles`
  ADD PRIMARY KEY (`id`),
  ADD KEY `contrato_detalles_contrato_id_foreign` (`contrato_id`),
  ADD KEY `contrato_detalles_proveedor_id_foreign` (`proveedor_id`),
  ADD KEY `contrato_detalles_producto_id_foreign` (`producto_id`);

--
-- Indices de la tabla `empresas`
--
ALTER TABLE `empresas`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `historial_accions`
--
ALTER TABLE `historial_accions`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `pagos`
--
ALTER TABLE `pagos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pagos_programacion_id_foreign` (`programacion_id`);

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `programacions`
--
ALTER TABLE `programacions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `programacions_contrato_id_foreign` (`contrato_id`),
  ADD KEY `programacions_empresa_id_foreign` (`empresa_id`),
  ADD KEY `programacions_asociacion_id_foreign` (`asociacion_id`),
  ADD KEY `programacions_producto_id_foreign` (`producto_id`),
  ADD KEY `programacions_proveedor_id_foreign` (`proveedor_id`),
  ADD KEY `programacions_vehiculo_id_foreign` (`vehiculo_id`),
  ADD KEY `programacions_conductor_id_foreign` (`conductor_id`);

--
-- Indices de la tabla `proveedors`
--
ALTER TABLE `proveedors`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_usuario_unique` (`usuario`);

--
-- Indices de la tabla `vehiculos`
--
ALTER TABLE `vehiculos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `vehiculos_conductor_id_foreign` (`conductor_id`);

--
-- Indices de la tabla `viajes`
--
ALTER TABLE `viajes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `viajes_programacion_id_foreign` (`programacion_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `asignacions`
--
ALTER TABLE `asignacions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `asignacion_detalles`
--
ALTER TABLE `asignacion_detalles`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `conductors`
--
ALTER TABLE `conductors`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `configuracions`
--
ALTER TABLE `configuracions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `contratos`
--
ALTER TABLE `contratos`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `contrato_detalles`
--
ALTER TABLE `contrato_detalles`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `empresas`
--
ALTER TABLE `empresas`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `historial_accions`
--
ALTER TABLE `historial_accions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT de la tabla `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de la tabla `pagos`
--
ALTER TABLE `pagos`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `programacions`
--
ALTER TABLE `programacions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `proveedors`
--
ALTER TABLE `proveedors`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `vehiculos`
--
ALTER TABLE `vehiculos`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `viajes`
--
ALTER TABLE `viajes`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `asignacions`
--
ALTER TABLE `asignacions`
  ADD CONSTRAINT `asignacions_contrato_id_foreign` FOREIGN KEY (`contrato_id`) REFERENCES `contratos` (`id`);

--
-- Filtros para la tabla `asignacion_detalles`
--
ALTER TABLE `asignacion_detalles`
  ADD CONSTRAINT `asignacion_detalles_empresa_id_foreign` FOREIGN KEY (`empresa_id`) REFERENCES `empresas` (`id`);

--
-- Filtros para la tabla `contrato_detalles`
--
ALTER TABLE `contrato_detalles`
  ADD CONSTRAINT `contrato_detalles_contrato_id_foreign` FOREIGN KEY (`contrato_id`) REFERENCES `contratos` (`id`),
  ADD CONSTRAINT `contrato_detalles_producto_id_foreign` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`),
  ADD CONSTRAINT `contrato_detalles_proveedor_id_foreign` FOREIGN KEY (`proveedor_id`) REFERENCES `proveedors` (`id`);

--
-- Filtros para la tabla `pagos`
--
ALTER TABLE `pagos`
  ADD CONSTRAINT `pagos_programacion_id_foreign` FOREIGN KEY (`programacion_id`) REFERENCES `programacions` (`id`);

--
-- Filtros para la tabla `programacions`
--
ALTER TABLE `programacions`
  ADD CONSTRAINT `programacions_asociacion_id_foreign` FOREIGN KEY (`asociacion_id`) REFERENCES `empresas` (`id`),
  ADD CONSTRAINT `programacions_conductor_id_foreign` FOREIGN KEY (`conductor_id`) REFERENCES `conductors` (`id`),
  ADD CONSTRAINT `programacions_contrato_id_foreign` FOREIGN KEY (`contrato_id`) REFERENCES `contratos` (`id`),
  ADD CONSTRAINT `programacions_empresa_id_foreign` FOREIGN KEY (`empresa_id`) REFERENCES `empresas` (`id`),
  ADD CONSTRAINT `programacions_producto_id_foreign` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`),
  ADD CONSTRAINT `programacions_proveedor_id_foreign` FOREIGN KEY (`proveedor_id`) REFERENCES `proveedors` (`id`),
  ADD CONSTRAINT `programacions_vehiculo_id_foreign` FOREIGN KEY (`vehiculo_id`) REFERENCES `vehiculos` (`id`);

--
-- Filtros para la tabla `vehiculos`
--
ALTER TABLE `vehiculos`
  ADD CONSTRAINT `vehiculos_conductor_id_foreign` FOREIGN KEY (`conductor_id`) REFERENCES `conductors` (`id`);

--
-- Filtros para la tabla `viajes`
--
ALTER TABLE `viajes`
  ADD CONSTRAINT `viajes_programacion_id_foreign` FOREIGN KEY (`programacion_id`) REFERENCES `programacions` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
