-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1:3306
-- Tiempo de generación: 17-06-2024 a las 04:20:05
-- Versión del servidor: 8.2.0
-- Versión de PHP: 8.2.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `digitech_produccion`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `alumno_cemergencia`
--

DROP TABLE IF EXISTS `alumno_cemergencia`;
CREATE TABLE IF NOT EXISTS `alumno_cemergencia` (
  `cemer_id` int NOT NULL AUTO_INCREMENT,
  `cemer_alumnoid` int NOT NULL,
  `cemer_nombre` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish2_ci DEFAULT NULL,
  `cemer_celular` varchar(10) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish2_ci DEFAULT NULL,
  `cemer_parentesco` varchar(3) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish2_ci DEFAULT NULL,
  PRIMARY KEY (`cemer_id`),
  KEY `cemer_alumnoid` (`cemer_alumnoid`)
) ENGINE=MyISAM AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_spanish2_ci;

--
-- Volcado de datos para la tabla `alumno_cemergencia`
--

INSERT INTO `alumno_cemergencia` (`cemer_id`, `cemer_alumnoid`, `cemer_nombre`, `cemer_celular`, `cemer_parentesco`) VALUES
(1, 1, 'Freddy Pinzon', '0993120984', '4PA'),
(2, 3, 'Maria del Cisne Zaruma', '0983516569', '4MA'),
(3, 4, 'Maria del Cisne Zaruma', '0983516569', '4MA'),
(4, 5, 'Rocio Alba', '0986752598', '4MA'),
(5, 6, 'Nicole Pesantez', '0995670116', '4MA'),
(6, 7, 'Katari Gualan', '0992972249', '4MA'),
(7, 8, 'Jennyfer Álvarez', '0996344221', '4HE'),
(8, 9, 'Alexis Córdova', '0980204393', '4PA'),
(9, 12, 'Jaime Guaicha', '0989361598', '4PA'),
(10, 13, 'Kevin Roman', '0998267482', '4MA'),
(11, 14, 'Diana Maribel Ordoñez', '0997774034', '4MA'),
(12, 15, 'Diana Maribel Ordoñez', '0997774034', '4MA'),
(13, 19, 'Lourdes Gordillo', '0959446942', '4MA'),
(14, 20, 'Marysabel Coronado', '0997824844', '4MA'),
(15, 21, 'Jessica Yupangui', '0990823533', '4HE'),
(16, 22, 'Juliana Jimenez', '0986902739', '4MA'),
(17, 23, 'Alicia Ramirez', '0980107592', '4AB'),
(18, 24, 'Magaly Angamarca', '0969668010', '4TI'),
(19, 26, 'Verónica Costaiza', '0980828086', '4MA'),
(20, 29, 'Bryan Díaz', '0990521693', '4MA'),
(21, 42, 'Hermerl Mendosa', '0980870385', '4PA'),
(22, 50, 'Cecilia Armijos', '0985209348', '4AB'),
(23, 76, 'Angel Caraguay', '0939837873', '4TI');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `alumno_documentos`
--

DROP TABLE IF EXISTS `alumno_documentos`;
CREATE TABLE IF NOT EXISTS `alumno_documentos` (
  `documento_id` int NOT NULL AUTO_INCREMENT,
  `documento_alumnoid` int NOT NULL,
  `documento_nombre` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish2_ci NOT NULL,
  `documento_detalle` varchar(300) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish2_ci DEFAULT NULL,
  `documento_documentoA` varchar(20) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish2_ci NOT NULL,
  `documento_documentoR` varchar(20) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish2_ci DEFAULT NULL,
  PRIMARY KEY (`documento_id`),
  KEY `documento_alumnoid` (`documento_alumnoid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_spanish2_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `alumno_infomedic`
--

DROP TABLE IF EXISTS `alumno_infomedic`;
CREATE TABLE IF NOT EXISTS `alumno_infomedic` (
  `infomedic_id` int NOT NULL AUTO_INCREMENT,
  `infomedic_alumnoid` int NOT NULL,
  `infomedic_fecha` date NOT NULL,
  `infomedic_tiposangre` varchar(5) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish2_ci DEFAULT NULL,
  `infomedic_peso` decimal(10,2) DEFAULT NULL,
  `infomedic_talla` decimal(10,2) DEFAULT NULL,
  `infomedic_enfermedad` varchar(200) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish2_ci DEFAULT NULL,
  `infomedic_medicamentos` varchar(300) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish2_ci DEFAULT NULL,
  `infomedic_alergia1` varchar(200) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish2_ci DEFAULT NULL,
  `infomedic_alergia2` varchar(200) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish2_ci DEFAULT NULL,
  `infomedic_cirugias` varchar(300) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish2_ci DEFAULT NULL,
  `infomedic_observacion` varchar(300) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish2_ci DEFAULT NULL,
  `infomedic_covid` char(1) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish2_ci DEFAULT NULL,
  `infomedic_vacunas` char(1) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish2_ci DEFAULT NULL,
  PRIMARY KEY (`infomedic_id`),
  KEY `infomedic_alumnoid` (`infomedic_alumnoid`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_spanish2_ci;

--
-- Volcado de datos para la tabla `alumno_infomedic`
--

INSERT INTO `alumno_infomedic` (`infomedic_id`, `infomedic_alumnoid`, `infomedic_fecha`, `infomedic_tiposangre`, `infomedic_peso`, `infomedic_talla`, `infomedic_enfermedad`, `infomedic_medicamentos`, `infomedic_alergia1`, `infomedic_alergia2`, `infomedic_cirugias`, `infomedic_observacion`, `infomedic_covid`, `infomedic_vacunas`) VALUES
(1, 1, '2024-05-29', 'ORH+', 35.00, 125.00, 'Alergia', 'Alercet', 'Ninguna', 'Ninguna', 'Ninguna', 'No es necesario medicamento cuando tiene mucho cansancio, la tos es normal', 'S', 'S');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `alumno_pago`
--

DROP TABLE IF EXISTS `alumno_pago`;
CREATE TABLE IF NOT EXISTS `alumno_pago` (
  `pago_id` int NOT NULL AUTO_INCREMENT,
  `pago_rubroid` varchar(3) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish2_ci NOT NULL,
  `pago_formapagoid` varchar(3) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish2_ci NOT NULL,
  `pago_alumnoid` int NOT NULL,
  `pago_valor` decimal(10,2) NOT NULL,
  `pago_saldo` decimal(10,2) DEFAULT NULL,
  `pago_concepto` varchar(300) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish2_ci DEFAULT NULL,
  `pago_fecha` date NOT NULL,
  `pago_fecharegistro` date NOT NULL,
  `pago_periodo` varchar(20) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish2_ci DEFAULT NULL,
  `pago_recibo` varchar(20) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish2_ci NOT NULL,
  `pago_estado` char(1) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish2_ci DEFAULT NULL,
  `pago_archivo` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish2_ci DEFAULT NULL,
  PRIMARY KEY (`pago_id`),
  KEY `pago_rubroid` (`pago_rubroid`),
  KEY `pago_formapagoid` (`pago_formapagoid`),
  KEY `pago_alumnoid` (`pago_alumnoid`)
) ENGINE=MyISAM AUTO_INCREMENT=89 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_spanish2_ci;

--
-- Volcado de datos para la tabla `alumno_pago`
--

INSERT INTO `alumno_pago` (`pago_id`, `pago_rubroid`, `pago_formapagoid`, `pago_alumnoid`, `pago_valor`, `pago_saldo`, `pago_concepto`, `pago_fecha`, `pago_fecharegistro`, `pago_periodo`, `pago_recibo`, `pago_estado`, `pago_archivo`) VALUES
(1, 'RPE', 'FTR', 16, 30.00, 0.00, 'Pago mes de mayo.', '2024-05-31', '2024-06-04', 'Mayo / 2024', '4041609542811', 'C', '16_98.jpg'),
(2, 'RPE', 'FTR', 16, 30.00, 0.00, 'Pago mes de mayo ($8 abonado y $22 total)', '2024-05-31', '2024-06-04', 'Mayo / 2024', '4041609542032', 'E', '16_80.jpg'),
(3, 'RPE', 'FTR', 16, 30.00, 0.00, 'Pago mes de mayo ($8 abonado y $22 total)', '2024-05-31', '2024-06-04', 'Mayo / 2024', '4051600042003', 'E', '16_11.jpg'),
(4, 'RPE', 'FTR', 16, 30.00, 0.00, 'Pago mes de mayo ($8 abonado y $22 total)', '2024-05-31', '2024-06-04', 'Mayo / 2024', '4051600042534', 'E', '16_55.jpg'),
(6, 'RIN', 'FEF', 19, 20.00, 0.00, 'Pago inscripción', '2024-06-04', '2024-06-04', 'junio', '4061608142546', 'C', ''),
(7, 'RPE', 'FEF', 19, 30.00, 0.00, '', '2024-06-04', '2024-06-04', 'Junio / 2024', '4061608142557', 'C', ''),
(8, 'RPE', 'FEF', 20, 30.00, 0.00, 'Pago mes junio', '2024-06-04', '2024-06-04', 'Junio / 2024', '4061605242438', 'C', ''),
(9, 'RPE', 'FEF', 21, 30.00, 0.00, 'Pago mes junio', '2024-06-04', '2024-06-04', 'Junio / 2024', '4061601442239', 'C', ''),
(10, 'RIN', 'FEF', 22, 20.00, 0.00, 'Pago inscripción', '2024-06-04', '2024-06-04', 'junio', '40616025429310', 'C', ''),
(11, 'RPE', 'FEF', 22, 30.00, 0.00, 'Pago mes junio', '2024-06-04', '2024-06-04', 'Junio / 2024', '40616025425511', 'C', ''),
(12, 'RPE', 'FEF', 23, 30.00, 0.00, 'Pago mes junio', '2024-06-04', '2024-06-04', 'Junio / 2024', '40716011429212', 'C', ''),
(13, 'RPE', 'FEF', 24, 30.00, 0.00, 'Pago mes de junio', '2024-06-04', '2024-06-04', 'Junio / 2024', '40816043424413', 'C', ''),
(14, 'RPE', 'FEF', 26, 30.00, 0.00, 'Pago mes junio.', '2024-06-05', '2024-06-05', 'Junio / 2024', '50416091422114', 'C', ''),
(15, 'RPE', 'FEF', 27, 30.00, 0.00, 'Pago mes de mayo', '2024-06-05', '2024-06-05', 'Mayo / 2024', '50516000422215', 'C', ''),
(16, 'RPE', 'FEF', 28, 30.00, 0.00, 'Pago mes de junio', '2024-06-05', '2024-06-05', 'Junio / 2024', '50516031424516', 'C', ''),
(17, 'RPE', 'FEF', 29, 30.00, 0.00, 'Pago mes de junio', '2024-06-05', '2024-06-05', 'Junio / 2024', '50616015429117', 'C', ''),
(18, 'RPE', 'FEF', 30, 30.00, 0.00, 'Pago mes de abril', '2024-04-05', '2024-06-05', 'Abril / 2024', '50716061422118', 'C', ''),
(19, 'RNU', 'FTR', 21, 100.00, 0.00, 'Pago kit 3 (Talla 36)', '2024-06-05', '2024-06-05', '2024-2026', '50716032429419', 'C', '21_21.jpg'),
(20, 'RPE', 'FEF', 33, 30.00, 0.00, 'Pago mes Junio', '2024-06-06', '2024-06-06', 'Junio / 2024', '60416090429520', 'C', ''),
(21, 'RNU', 'FEF', 34, 100.00, 0.00, '', '2024-06-06', '2024-06-06', '2024-2026', '60416081428121', 'C', ''),
(22, 'RPE', 'FEF', 35, 30.00, 0.00, 'Pago mes de junio', '2024-06-06', '2024-06-06', 'Junio / 2024', '60416012428322', 'C', ''),
(23, 'RNU', 'FEF', 35, 70.00, 0.00, 'Pago kit 1', '2024-06-06', '2024-06-06', '2024-2026', '60416032428023', 'C', ''),
(24, 'RPE', 'FTR', 37, 25.00, 0.00, 'Pago mes de junio', '2024-06-06', '2024-06-06', 'Junio / 2024', '60416014424224', 'C', '37_75.jpg'),
(25, 'RPE', 'FTR', 38, 25.00, 0.00, 'Pago mes de junio', '2024-06-06', '2024-06-06', 'Junio / 2024', '60416024427525', 'C', '38_8.jpg'),
(26, 'RPE', 'FEF', 39, 30.00, 0.00, 'Pago mes de junio', '2024-06-06', '2024-06-06', 'Junio / 2024', '60516020426326', 'C', ''),
(27, 'RPE', 'FEF', 7, 30.00, 0.00, 'pago.', '2024-06-06', '2024-06-06', 'Junio / 2024', '60516011429127', 'C', ''),
(28, 'RPE', 'FTR', 1, 30.00, 0.00, 'Pago mes de junio', '2024-06-06', '2024-06-06', 'Junio / 2024', '60516092425528', 'C', '1_52.jpg'),
(29, 'RPE', 'FEF', 40, 30.00, 0.00, 'Pago mes de junio', '2024-06-06', '2024-06-06', 'Junio / 2024', '60516034428329', 'C', ''),
(30, 'RNU', 'FTR', 41, 100.00, 0.00, 'Pago kit 3', '2024-06-06', '2024-06-06', '2024-2026', '60616031428030', 'C', '41_6.jpg'),
(31, 'RPE', 'FEF', 42, 30.00, 0.00, 'Pago mes de junio', '2024-06-06', '2024-06-06', 'Junio / 2024', '60616045422331', 'E', ''),
(32, 'RNU', 'FEF', 42, 100.00, 0.00, 'Pago kit 3', '2024-06-06', '2024-06-06', '2024-2026', '60616065423232', 'E', ''),
(33, 'RPE', 'FEF', 42, 30.00, 0.00, 'Pago mes de junio', '2024-06-06', '2024-06-06', 'Junio / 2024', '60616085422033', 'C', ''),
(34, 'RNU', 'FEF', 42, 30.00, 70.00, 'Abono $30 kit 3', '2024-06-06', '2024-06-06', '2024-2026', '60616095421234', 'P', ''),
(35, 'RPE', 'FEF', 46, 30.00, 0.00, 'Pago mes febrero', '2024-02-06', '2024-06-06', 'Febrero / 2024', '60716025422135', 'C', ''),
(36, 'RPE', 'FEF', 46, 30.00, 0.00, 'Pago mes marzo', '2024-03-06', '2024-06-06', 'Marzo  / 2024', '60716025428536', 'C', ''),
(37, 'RPE', 'FEF', 46, 30.00, 0.00, 'Pago mes abril', '2024-04-06', '2024-06-06', 'Abril / 2024', '60716035425237', 'C', ''),
(38, 'RPE', 'FEF', 46, 10.00, 20.00, 'Abono mes de mayo', '2024-05-06', '2024-06-06', 'Mayo / 2024', '60716045421038', 'P', ''),
(39, 'RPE', 'FEF', 47, 30.00, 0.00, 'Pago mes de junio', '2024-06-06', '2024-06-06', 'Junio / 2024', '60816011429239', 'C', ''),
(40, 'RNU', 'FEF', 48, 100.00, 0.00, 'Pago kit 3', '2024-06-06', '2024-06-06', '2024-2026', '60816071426040', 'C', ''),
(41, 'RPE', 'FEF', 48, 30.00, 0.00, 'Pago mes de junio', '2024-06-06', '2024-06-06', 'Junio / 2024', '60816071423341', 'C', ''),
(42, 'RNU', 'FTR', 9, 70.00, 0.00, 'Pago kit 1', '2024-06-05', '2024-06-06', '2024-2026', '60816012424142', 'C', '9_84.jpg'),
(43, 'RNU', 'FEF', 24, 100.00, 0.00, 'Pago kit 3', '2024-06-06', '2024-06-06', '2024-2026', '60916030429543', 'C', ''),
(44, 'RPE', 'FEF', 49, 30.00, 0.00, 'Pago mes de marzo', '2024-03-07', '2024-06-07', 'Marzo / 2024', '70116012428144', 'C', ''),
(45, 'RPE', 'FEF', 49, 30.00, 0.00, 'Pago mes abril', '2024-04-07', '2024-06-07', 'Abril / 2024', '70116022428345', 'C', ''),
(46, 'RPE', 'FEF', 49, 0.00, 0.00, 'No asistió mes de mayo', '2024-05-07', '2024-06-07', 'Mayo / 2024', '70116042420046', 'J', ''),
(47, 'RPE', 'FEF', 49, 30.00, 0.00, 'Pago mes de junio', '2024-06-07', '2024-06-07', 'Junio / 2024', '70116042427347', 'C', ''),
(48, 'RNU', 'FTR', 50, 100.00, 0.00, 'Pago kit 3', '2024-06-07', '2024-06-07', '2024-2026', '70316063424548', 'C', '50_89.jpg'),
(49, 'RNU', 'FTR', 51, 80.00, 20.00, 'Abono kit 3', '2024-06-07', '2024-06-07', '2024-2026', '70416062423049', 'P', '51_94.jpg'),
(50, 'RNU', 'FEF', 52, 25.00, 75.00, 'Abono kit 3', '2024-06-07', '2024-06-07', '2024-2026', '70516031428350', 'P', ''),
(51, 'RNU', 'FEF', 53, 25.00, 75.00, 'Abono kit 3', '2024-06-07', '2024-06-07', '2024-2026', '70516041423351', 'P', ''),
(52, 'RPE', 'FEF', 53, 25.00, 0.00, 'Pago mes de junio', '2024-06-07', '2024-06-07', 'Junio / 2024', '70516081428052', 'C', ''),
(53, 'RPE', 'FEF', 52, 25.00, 0.00, 'Pago mes de junio', '2024-06-07', '2024-06-07', 'Junio / 2024', '70516081426553', 'C', ''),
(54, 'RPE', 'FEF', 55, 0.00, 0.00, 'No asiste por vacaciones', '2024-06-07', '2024-06-07', 'Junio / 2024', '70516064422154', 'J', ''),
(55, 'RPE', 'FTR', 54, 30.00, 0.00, 'Pago mes de junio ($1 amistoso )', '2024-06-07', '2024-06-07', 'Junio / 2024', '70516084425155', 'C', '54_36.jpg'),
(56, 'RPE', 'FEF', 56, 0.00, 0.00, 'No asiste por vacaciones', '2024-06-07', '2024-06-07', 'Junio / 2024', '70516025427456', 'J', ''),
(57, 'RNU', 'FTR', 44, 60.00, 0.00, 'Pago dos uniformes entrenamiento', '2024-06-07', '2024-06-07', '2024-2026', '70616010428557', 'C', '44_64.jpg'),
(58, 'RPE', 'FEF', 57, 30.00, 0.00, 'Pago mes de junio', '2024-06-07', '2024-06-07', 'Junio / 2024', '70616021428058', 'C', ''),
(59, 'RNU', 'FEF', 57, 100.00, 0.00, 'Pago kit 3', '2024-06-07', '2024-06-07', '2024-2026', '70616021423459', 'C', ''),
(60, 'RNU', 'FEF', 20, 70.00, 0.00, 'Pago kit 1', '2024-06-07', '2024-06-07', '2024-2026', '70616081420360', 'C', ''),
(61, 'RNU', 'FEF', 58, 50.00, 50.00, 'Abono kit 3', '2024-06-07', '2024-06-07', '2024-2026', '70616052425061', 'P', ''),
(62, 'RPE', 'FEF', 44, 25.00, 0.00, 'Pago mes junio', '2024-06-06', '2024-06-07', 'Junio / 2024', '70616013420062', 'C', ''),
(63, 'RPE', 'FTR', 45, 25.00, 0.00, 'Pago mes junio', '2024-06-06', '2024-06-07', 'Junio / 2024', '70616013427363', 'C', ''),
(64, 'RPE', 'FEF', 61, 30.00, 0.00, 'Pago mes de junio', '2024-06-07', '2024-06-07', 'Junio / 2024', '70716010422464', 'C', ''),
(65, 'RPE', 'FEF', 62, 30.00, 0.00, 'Pago mes de junio', '2024-06-07', '2024-06-07', 'Junio / 2024', '70716060424465', 'C', ''),
(66, 'RPE', 'FEF', 59, 25.00, 0.00, 'Pago mes de marzo', '2024-03-07', '2024-06-07', 'Marzo / 2024', '70716031422166', 'C', ''),
(67, 'RPE', 'FEF', 59, 25.00, 0.00, 'Pago mes abril', '2024-04-07', '2024-06-07', 'Abril / 2024', '70716031429367', 'C', ''),
(68, 'RPE', 'FEF', 59, 25.00, 0.00, 'Pago mes de mayo', '2024-05-07', '2024-06-07', 'Mayo/2024', '70716041420268', 'C', ''),
(69, 'RPE', 'FEF', 60, 30.00, 0.00, 'Pago mes de marzo', '2024-03-07', '2024-06-07', 'Marzo / 2024', '70716061427569', 'C', ''),
(70, 'RPE', 'FEF', 60, 30.00, 0.00, 'Pago mes de abril.', '2024-04-07', '2024-06-07', 'Abril / 2024', '70716071425170', 'C', ''),
(71, 'RPE', 'FEF', 60, 30.00, 0.00, 'Pago mes mayo', '2024-05-07', '2024-06-07', 'Mayo/ 2024', '70716071428471', 'C', ''),
(72, 'RPE', 'FEF', 63, 25.00, 0.00, 'Pago mes de junio', '2024-06-07', '2024-06-07', 'Junio / 2024', '70916020420272', 'C', ''),
(73, 'RPE', 'FEF', 64, 25.00, 0.00, 'Pago mes de junio', '2024-06-07', '2024-06-07', 'Junio / 2024', '70916020421473', 'C', ''),
(74, 'RPE', 'FEF', 65, 20.00, 0.00, 'Pago mes de junio (Ya tenía abonado $10)', '2024-06-10', '2024-06-10', 'Junio / 2024', '01416030425274', 'C', ''),
(75, 'RPE', 'FEF', 67, 25.00, 0.00, 'Pago mes de junio', '2024-06-10', '2024-06-10', 'Junio / 2024', '01416083429375', 'C', ''),
(76, 'RPE', 'FEF', 66, 25.00, 0.00, 'Pago mes de junio', '2024-06-10', '2024-06-10', 'Junio / 2024', '01416093429176', 'C', ''),
(77, 'RPE', 'FEF', 68, 30.00, 0.00, 'Pago mes de Junio', '2024-06-10', '2024-06-10', 'Junio / 2024', '01416054423077', 'E', ''),
(78, 'RNU', 'FEF', 13, 70.00, 0.00, 'Pago kit 1', '2024-06-10', '2024-06-10', '2024-2026', '01416084426378', 'C', ''),
(79, 'RPE', 'FEF', 69, 30.00, 0.00, 'Pago mes junio', '2024-06-10', '2024-06-10', 'Junio / 2024', '01416065426079', 'C', ''),
(80, 'RPE', 'FEF', 70, 30.00, 0.00, 'Pago  mes junio', '2024-06-10', '2024-06-10', 'Junio / 2024', '01516020422580', 'C', ''),
(81, 'RPE', 'FTR', 73, 30.00, 0.00, 'Pago mes de junio', '2024-06-10', '2024-06-10', 'Junio / 2024', '01516002424481', 'C', '73_19.jpg'),
(82, 'RNU', 'FEF', 63, 50.00, 10.00, 'Pago dos uniformes de entrenamiento', '2024-06-10', '2024-06-10', '2024-2026', '01516083421582', 'P', ''),
(83, 'RPE', 'FEF', 74, 30.00, 0.00, 'Pago mes de junio', '2024-06-10', '2024-06-10', 'Junio / 2024', '01516094428183', 'C', ''),
(84, 'RPE', 'FEF', 75, 30.00, 0.00, 'Pago mes de junio (más  $1 de partido amistoso)', '2024-06-10', '2024-06-10', 'Junio / 2024', '01516075425584', 'C', ''),
(85, 'RNU', 'FEF', 26, 50.00, 50.00, 'Pago kit 3', '2024-06-10', '2024-06-10', '2024-2026', '01616050422385', 'P', ''),
(86, 'RIN', 'FEF', 76, 20.00, 0.00, 'Pago inscripción', '2024-06-10', '2024-06-10', '2024', '01616081424486', 'C', ''),
(87, 'RPE', 'FEF', 76, 30.00, 0.00, 'Pago mes de junio', '2024-06-10', '2024-06-10', 'Junio / 2024', '01616091425087', 'C', ''),
(88, 'RPE', 'FEF', 68, 20.00, 0.00, 'Pago mes junio', '2024-06-10', '2024-06-10', 'Junio / 2024', '01616014420088', 'C', '');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `alumno_pago_descuento`
--

DROP TABLE IF EXISTS `alumno_pago_descuento`;
CREATE TABLE IF NOT EXISTS `alumno_pago_descuento` (
  `descuento_id` int NOT NULL AUTO_INCREMENT,
  `descuento_rubroid` varchar(3) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish2_ci NOT NULL,
  `descuento_alumnoid` int NOT NULL,
  `descuento_valor` decimal(10,2) NOT NULL,
  `descuento_detalle` varchar(300) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish2_ci DEFAULT NULL,
  `descuento_fecha` date NOT NULL,
  `descuento_estado` char(1) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish2_ci DEFAULT NULL,
  PRIMARY KEY (`descuento_id`),
  KEY `descuento_alumnoid` (`descuento_alumnoid`),
  KEY `descuento_rubroid` (`descuento_rubroid`)
) ENGINE=MyISAM AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_spanish2_ci;

--
-- Volcado de datos para la tabla `alumno_pago_descuento`
--

INSERT INTO `alumno_pago_descuento` (`descuento_id`, `descuento_rubroid`, `descuento_alumnoid`, `descuento_valor`, `descuento_detalle`, `descuento_fecha`, `descuento_estado`) VALUES
(1, 'DBC', 8, 0.00, 'Por mérito deportivo', '2024-06-04', 'S'),
(2, 'DDS', 3, 25.00, 'Descuento por hermano', '2024-06-05', 'S'),
(3, 'DDS', 37, 25.00, 'Descuento por hermano', '2024-06-06', 'S'),
(4, 'DDS', 38, 25.00, 'Descuento por hermano', '2024-06-06', 'S'),
(5, 'DDS', 53, 25.00, 'Descuento por hermanos', '2024-06-07', 'N'),
(6, 'DDS', 53, 25.00, 'Descuento por hermanos', '2024-06-07', 'S'),
(7, 'DDS', 52, 25.00, 'Descuento por hermano', '2024-06-07', 'S'),
(8, 'DDS', 44, 25.00, 'Descuento por hermanos', '2024-06-07', 'S'),
(9, 'DDS', 45, 25.00, 'Descuento por hermano', '2024-06-07', 'S'),
(10, 'DDS', 59, 25.00, 'Descuento por hermano', '2024-06-07', 'S'),
(11, 'DDS', 60, 25.00, 'Descuento por hermano', '2024-06-07', 'S'),
(12, 'DDS', 60, 25.00, 'Descuento por hermano', '2024-06-07', 'S'),
(13, 'DDS', 63, 25.00, 'Descuento por hermano', '2024-06-07', 'S'),
(14, 'DDS', 64, 25.00, 'Descuento por hermano', '2024-06-07', 'S'),
(15, 'DDS', 66, 25.00, 'Descuento por hermano', '2024-06-10', 'S'),
(16, 'DDS', 67, 25.00, 'Descuento por hermano', '2024-06-10', 'S'),
(17, 'DDS', 68, 20.00, 'Descuento por familiar', '2024-06-10', 'S');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `alumno_pago_transaccion`
--

DROP TABLE IF EXISTS `alumno_pago_transaccion`;
CREATE TABLE IF NOT EXISTS `alumno_pago_transaccion` (
  `transaccion_id` int NOT NULL AUTO_INCREMENT,
  `transaccion_pagoid` int NOT NULL,
  `transaccion_valorcalculado` decimal(10,2) NOT NULL,
  `transaccion_valor` decimal(10,2) NOT NULL,
  `transaccion_fecha` date NOT NULL,
  `transaccion_fecharegistro` date NOT NULL,
  `transaccion_formapagoid` varchar(3) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish2_ci NOT NULL,
  `transaccion_concepto` varchar(300) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish2_ci DEFAULT NULL,
  `transaccion_periodo` varchar(20) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish2_ci NOT NULL,
  `transaccion_recibo` varchar(20) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish2_ci NOT NULL,
  `transaccion_estado` char(1) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish2_ci DEFAULT NULL,
  `transaccion_archivo` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish2_ci DEFAULT NULL,
  PRIMARY KEY (`transaccion_id`),
  KEY `transaccion_pagoid` (`transaccion_pagoid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_spanish2_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `alumno_representante`
--

DROP TABLE IF EXISTS `alumno_representante`;
CREATE TABLE IF NOT EXISTS `alumno_representante` (
  `repre_id` int NOT NULL AUTO_INCREMENT,
  `repre_alumnoid` int NOT NULL,
  `repre_tipoidentificacion` varchar(3) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish2_ci NOT NULL,
  `repre_identificacion` varchar(13) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish2_ci NOT NULL,
  `repre_primernombre` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish2_ci NOT NULL,
  `repre_segundonombre` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish2_ci DEFAULT NULL,
  `repre_apellidopaterno` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish2_ci NOT NULL,
  `repre_apellidomaterno` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish2_ci DEFAULT NULL,
  `repre_direccion` varchar(400) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish2_ci NOT NULL,
  `repre_correo` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish2_ci NOT NULL,
  `repre_celular` varchar(10) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish2_ci NOT NULL,
  `repre_sexo` varchar(1) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish2_ci DEFAULT NULL,
  `repre_parentesco` varchar(3) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish2_ci NOT NULL,
  PRIMARY KEY (`repre_id`),
  KEY `repre_alumnoid` (`repre_alumnoid`)
) ENGINE=MyISAM AUTO_INCREMENT=77 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_spanish2_ci;

--
-- Volcado de datos para la tabla `alumno_representante`
--

INSERT INTO `alumno_representante` (`repre_id`, `repre_alumnoid`, `repre_tipoidentificacion`, `repre_identificacion`, `repre_primernombre`, `repre_segundonombre`, `repre_apellidopaterno`, `repre_apellidomaterno`, `repre_direccion`, `repre_correo`, `repre_celular`, `repre_sexo`, `repre_parentesco`) VALUES
(1, 1, 'CED', '1103345292', 'Freddy', 'Bolivar', 'Pinzon', 'Olmedo', 'Rey David 410-34 y Juan el Bautista', 'fbpinzon@gmail.com', '0993120984', 'M', '4PA'),
(2, 2, 'CED', '1104429947', 'Amparo', 'Jackeline', 'Castillo', 'Manchay', 'Cdla Daniel Álvarez', 'djmanuelmanchaym24@gmail.com', '0959278278', 'F', '4MA'),
(3, 3, 'CED', '1104709520', 'Maria del Cisne', '', 'Zaruma', 'Romero', 'Colón e/ Sucre y 18 de noviembre', 'jvcueva@hotmail.com', '0983516569', 'F', '4MA'),
(4, 4, 'CED', '1104709520', 'Maria del Cisne', '', 'Zaruma', 'Romero', 'Colón e/ Sucre y 18 de Noviembre', 'jvcueva@hotmail.com', '0983516569', 'F', '4MA'),
(5, 5, 'CED', '1900794502', 'Maria', 'Rocio', 'Alba', 'Lucero', 'Ciudad Alegría, Av. Condamine', 'danna0701@outlook.es', '0986752598', 'F', '4MA'),
(6, 6, 'CED', '1106043290', 'Nicole', 'Anahi', 'Pesantez', 'Ortega', 'Azuay y Sucre', 'nico2017mati@gmail.com', '0995670116', 'F', '4MA'),
(7, 7, 'CED', '0201592540', 'Luis', 'Joaquin', 'Gualan', 'Japon', 'Colón y Lauro Guerrero', 'luisgualan@gmail.com', '0986814560', 'M', '4PA'),
(8, 8, 'CED', '1103533913', 'Luis', 'Roberto', 'Alvarez', 'Granda', 'Cdla. Daniel Álvarez', 'luis86567@gmail.com', '0979689777', 'M', '4MA'),
(9, 9, 'CED', '1105236259', 'Gabriela', 'Fernanda', 'Luzon', 'Cevallos', 'Zarzas II', 'gafer_094@hotmail.com', '0997990000', 'F', '4MA'),
(10, 10, 'CED', '1104539109', 'Bremilda', 'Yaneth', 'Rosales', 'Jimenez', 'Operadores', 'yanesebas292012@gmail.com', '0992109803', 'F', '4MA'),
(11, 11, 'CED', '1104539109', 'Bremilda', 'Yaneth', 'Rosales', 'Jimenez', 'Operadores', 'yanesebas292012@gmail.com', '0992109803', 'F', '4MA'),
(12, 12, 'CED', '1104696719', 'Verónica', 'Elizabeth', 'Jimenez', 'Luna', 'Unión Lojana', 'vj322043@gmail.com', '0981681207', 'F', '4MA'),
(13, 13, 'CED', '1104232309', 'Edwin', 'Patricio', 'Sarango', 'Díaz', 'Daniel Álvarez, Francisco de Miranda y Miguel Hidalgo', 'patobang2@gmail.com', '0993143941', 'M', '4PA'),
(14, 14, 'CED', '1103881122', 'Fernando', 'Elias', 'Cueva', 'Calderón', 'Los Geraneos', 'fernandocuevac@gmail.com', '0994366470', 'M', '4PA'),
(15, 15, 'CED', '1103881122', 'Fernando', 'Elias', 'Cueva', 'Calderón', 'Los Geraneos', 'fernandocuevac@gmail.com', '0994366470', 'M', '4PA'),
(16, 16, 'CED', '1104722036', 'Jose', 'Antonio', 'Maurad', 'Jadan', 'Sucre', 'joseantoniomaurad@gmail.com', '0986838382', 'M', '4PA'),
(17, 17, 'CED', '0704746676', 'Cintia', 'del Rocío', 'Hernandez', 'Maza', 'Barrio El Paraíso', 'cintiahernandez1907@gmail.com', '0981402882', 'F', '4MA'),
(18, 18, 'CED', '0704746676', 'Cintia', 'del Rocío', 'Hernandez', 'Maza', 'Barrio El Paraíso', 'cintiahernandez1907@gmail.com', '0981402882', 'F', '4MA'),
(19, 19, 'CED', '1104498264', 'Lourdes', 'Elizabeth', 'Gordillo', 'Vasquez', 'San Cayetano', 'lou04eli@gmail.com', '0959446942', 'F', '4MA'),
(20, 20, 'CED', '1104014327', 'Marysabel', '', 'Coronado', 'Vallejo', 'Época', 'marysabelcv81@gmail.com', '0997824844', 'F', '4MA'),
(21, 21, 'CED', '1103029557', 'Maria', 'Elena', 'Ortega', 'Rodriguez', 'Colinas Lojanas', 'mariaelenaortegarodriguez2@gmail.com', '0967957378', 'F', '4MA'),
(22, 22, 'CED', '1104465537', 'Diego', 'Vinicio', 'Sisalima', '', 'Av. Eugenio Espejo (entrada a Santa Ines)', 'dinero98dj@gmail.com', '0980216253', 'M', '4PA'),
(23, 23, 'CED', '2100285796', 'Marly', 'Yesenia', 'Romero', 'Ramirez', 'Punzara chico', 'marly-3@hotmail.com', '0990922643', 'F', '4MA'),
(24, 24, 'CED', '1104803216', 'Zaida', 'Celena', 'Angamarca', 'Angamarca', 'Turunuma alto', 'zaidanga1996@gmail.com', '0961759711', 'F', '4MA'),
(25, 25, 'CED', '2100351697', 'Silvia', 'Ana', 'Chiliquinga', 'Chanaluisa', 'Sauces Norte, Salvador Dalí y Bustamante Celi', 'silviachiliquinga8@gmail.com', '0980267635', 'F', '4MA'),
(26, 26, 'CED', '1103835243', 'Darwin', 'Fabian', 'Veintimilla', 'Camacho', 'Dolorosa , Juan Bautista Aguirre y Jorge Icaza', 'darwinfaby@hotmail.com', '0986193162', 'M', '4PA'),
(27, 27, 'CED', '1150141560', 'Angel', 'Stalin', 'Serrano', 'Riofrio', 'Tierras Coloradas, Victor Emilio Valdiviezo', 'angelandresxxx@gmail.com', '0986893074', 'M', '4PA'),
(28, 28, 'CED', '1103916308', 'Álvaro', 'Fernando', 'Hurtado', 'Orellana', 'Operadores', 'alvarofernando31@hotmail.com', '0993862402', 'M', '4PA'),
(29, 29, 'CED', '0702972324', 'Edgar', 'Vicente', 'Diaz', 'Obaco', 'Julio Ordoñez y Ricardo Fernández', 'diazedgarv@yahoo.es', '0981709110', 'M', '4PA'),
(30, 31, 'CED', '1104973464', 'Maria', 'Eugenia', 'Vera', 'Armijos', 'Daniel Álvarez', 'maryvera091988@gmail.com', '0989895554', 'F', '4MA'),
(31, 32, 'CED', '1103803159', 'Lucrecia', 'Victoria', 'Encarnación', 'Ludeña', 'El Pedestal', 'luvienlu1980@gmail.com', '0982218969', 'F', '4MA'),
(32, 33, 'CED', '1104158330', 'Andrea', 'del Cisne', 'Perez', 'Gaona', 'Colinas Lojanas', 'andreacisne@hotmail.es', '0959662041', 'F', '4MA'),
(33, 34, 'CED', '1104302771', 'Karina', '', 'Bueno', 'Garrido', 'Turunuma', 'kary713@hotmail.com', '0969585974', 'F', '4MA'),
(34, 35, 'CED', '1105544967', 'Alejandra', 'Anabel', 'Enriquez', 'Merecí', 'Julio Jaramillo y Emer Vaca', 'aenriquezmereci@gmail.com', '0968623502', 'F', '4MA'),
(35, 36, 'CED', '1104113053', 'Gabriela', 'Paola', 'Beltran', 'Troya', 'Sol de los Andes', 'gabypbt@hotmail.com', '0980573872', 'F', '4MA'),
(36, 37, 'CED', '1900501907', 'Eduardo', 'Luis', 'Romero', 'León', 'Daniel Álvarez', 'teceduardo_rom@hotmail.es', '0986624612', 'M', '4PA'),
(37, 38, 'CED', '1900501907', 'Eduardo', 'Luis', 'Romero', 'León', 'Daniel Álvarez', 'teceduardo_rom@hotmail.com', '0986624612', 'M', '4PA'),
(38, 39, 'CED', '1103317184', 'Eduardo', 'Fabian', 'Atarihuana', 'Sandoval', 'Portugal y Filipinas', 'fabyedu@hotmail.com', '0993042779', 'M', '4PA'),
(39, 40, 'CED', '1104903271', 'Tania', '', 'Cuenca', 'Benavides', 'Época', 'ticuenca@gmail.com', '0959096849', 'F', '4MA'),
(40, 41, 'CED', '1104496474', 'Katy', 'Alexandra', 'Viñan', 'Torres', 'Portugal e/ Brasil y Quebec', 'alexandrk02@hotmail.com', '0992283315', 'F', '4MA'),
(41, 42, 'CED', '1104196421', 'Angélica', 'Cecibel', 'Jaramillo', 'Poma', 'Época, Estados Unidos y Alemania', 'anguie-1983@hotmail.com', '0990855059', 'F', '4MA'),
(42, 43, 'CED', '1150322053', 'Veronica', 'Elizabeth', 'Matailo', 'Sigcho', 'Barrio Belén', 'Veronicamatailo47@gmail.com', '0991675195', 'F', '4MA'),
(43, 44, 'CED', '1104960800', 'Ximena', 'Lucia', 'Carrasco', 'Ruiz', 'Barrio Daniel Alvarez', 'xime_lu93@hotmail.com', '0993343591', 'F', '4MA'),
(44, 45, 'CED', '1104960800', 'Ximena', 'Lucia', 'Carrasco', 'Ruiz', 'Barrio Daniel Alvarez', 'xime_lu93@hotmail.com', '0993343591', 'F', '4MA'),
(45, 46, 'CED', '1104980592', 'Vanessa', 'Alexandra', 'Guevara', 'Sanchez', 'Chontacruz', 'vaneguevaras17@gmail.com', '0959411128', 'F', '4MA'),
(46, 47, 'CED', '1102705819', 'Enma', 'Victoria', 'Romero', 'Torres', 'Turunuma alto', 'venma67@hotmail.com', '0985765933', 'F', '4AB'),
(47, 48, 'CED', '1105811937', 'Ana', 'Gabriela', 'Carrion', 'Cano', 'El Rosal', 'anitagcarrion1993@gmail.com', '0991792015', 'F', '4MA'),
(48, 49, 'CED', '1104488356', 'Briggett', 'Lucía', 'Rojas', 'Romero', 'Canteras del Pinar', 'briggettrojas0@gmail.com', '0985638262', 'F', '4MA'),
(49, 50, 'CED', '1105675969', 'Jessica', 'Elizabeth', 'Diaz', 'Armijos', 'Colinas Lojanas', 'je_kita1730@hotmail.com', '0993095287', 'F', '4MA'),
(50, 51, 'CED', '1103746960', 'Mercedes', '', 'Cano', '', 'Daniel Álvarez, Domingo Sarmiento y Jose de Artigas', 'mercedescano@hotmail.es', '0985981515', 'F', '4MA'),
(51, 52, 'CED', '1715775878', 'Cecilia', 'Inés', 'Sacon', 'Muñoz', 'Lote Bonito', 'jimenezenderson7@gmail.com', '0986205812', 'F', '4MA'),
(52, 53, 'CED', '1715775878', 'Cecilia', 'Inés', 'Sacon', 'Muñoz', 'Lote Bonito', 'jimenezenderson7@gmail.com', '0986205812', 'F', '4MA'),
(53, 54, 'CED', '1103938740', 'Carlos', 'Mauricio', 'Sanchez', '', 'Sol de los Andes', 'karlit.s@hotmail.com', '0985983885', 'M', '4MA'),
(54, 55, 'CED', '1104401177', 'Carmen', 'Victoria', 'Jacome', 'Santos', 'El Peñón', 'vicosanty11@gmail.com', '0958821031', 'F', '4MA'),
(55, 56, 'CED', '1105228496', 'Katy', 'Jessenia', 'Vacacela', 'Ambuludi', 'Av. Manuel Carrión Pinzano y Rocafuerte', 'katyvacacela93@gmail.com', '0993555515', 'F', '4MA'),
(56, 57, 'CED', '1151770532', 'Doris', 'Marlene', 'Prado', 'Salinas', 'Juan José Samaniego', 'doritasbeiapradosalinas@gmail.com', '0990102033', 'F', '4MA'),
(57, 58, 'CED', '1103447841', 'Anelio', 'Omar', 'Torres', 'Rueda', 'Época, Brasil y Honduras', 'mibuenpan@outlook.es', '0985132512', 'M', '4PA'),
(58, 59, 'CED', '0963489068', 'Miguel', 'Angel', 'Mejia', 'Quintero', 'Ciudad Alegría', 'miguelangelmejiaquintero@gmail.com', '0995401661', 'M', '4PA'),
(59, 60, 'CED', '0963498068', 'Miguel', 'Angel', 'Mejia', 'Quintero', 'Ciudad Alegría', 'miguelangelmejiaquintero2021@gmail.com', '0995401661', 'M', '4PA'),
(60, 61, 'CED', '1600393910', 'Paul', 'Alejandro', 'Celi', 'Carrion', 'Esteban Godoy', 'pulceli@gmail.com', '0980151227', 'M', '4PA'),
(61, 62, 'CED', '1103158943', 'Geovanny', 'Joel', 'Garcia', 'Torres', 'La Peñas, Mercadillo', 'geovannymiusic@yahoo.es', '0980940966', 'M', '4PA'),
(62, 63, 'CED', '1104490584', 'Claudia', 'Johana', 'Velepucha', 'Ovaco', 'Avenida de los Paltas y Herminia Jaramillo 30-53, Ciudadela Época.', 'claudia.velepucha10@gmail.com', '0994261790', 'M', '4MA'),
(63, 64, 'CED', '1104490584', 'Claudia', 'Johana', 'Velepucha', 'Ovaco', 'Avenida de los Paltas y Herminia Jaramillo 30-53, Ciudadela Época.', 'claudia.velepucha10@gmail.com', '0994261790', 'F', '4MA'),
(64, 65, 'CED', '1104993660', 'Johana', 'Lucía', 'Jimenez', 'Manchay', 'Hernán Cortez y Porfirio Díaz', 'joajimenez1991@hotmail.com', '0963764505', 'F', '4MA'),
(65, 66, 'CED', '1104966153', 'Ruth', 'Maria', 'Vargas', 'Villalta', 'Operadores', 'maria.vargas.villalta@gmail.com', '0988264248', 'F', '4MA'),
(66, 67, 'CED', '1104966153', 'Ruth', 'Maria', 'Vargas', 'Villalta', 'Operadores', 'maria.vargas.villalta@gmail.com', '0988264248', 'F', '4MA'),
(67, 68, 'CED', '1103875090', 'Maria', 'Eugenia', 'Enriquez', 'Rey', 'Daniel Álvarez', 'mao_enri@live.com', '0985771703', 'F', '4MA'),
(68, 69, 'CED', '1105649238', 'Maritza', 'Narciza', 'Jimenez', 'Abad', 'Av. Isidro Ayora y Puebla (A media cuadra de la gasolinera de cooperativa Loja)', 'jeff-tm95@hotmail.com', '0994022079', 'F', '4MA'),
(69, 70, 'CED', '1104810450', 'Michelle', 'Paulina', 'Jimenez', 'Gonzalez', 'Sol de los Andes', 'paulina20171994@outlook.es', '0981019735', 'F', '4MA'),
(70, 71, 'CED', '1103214118', 'Henry', 'Francisco', 'Cueva', 'Bravo', 'Cuero y Caicedo', 'cubra.sgr@gmail.com', '0994068399', 'M', '4PA'),
(71, 72, 'CED', '1103214118', 'Henry', 'Francisco', 'Cueva', 'Bravo', 'Cuero y Caicedo', 'cubra.sgr@gmail.com', '0994068399', 'M', '4MA'),
(72, 73, 'CED', '1103762199', 'Rafael', 'Carlos', 'Reyes', 'Pesantez', 'Bolívar e Imbabura', 'rafaelreyesseguros@gmail.com', '0981028319', 'M', '4MA'),
(73, 74, 'CED', '1104454747', 'Manuel', 'Oswaldo', 'Salinas', 'Gonzalez', 'Sabiango e/ Lourdes y  Leopoldo Palacios', 'oswaldosalinas255@gmail.com', '0989394779', 'M', '4PA'),
(74, 75, 'CED', '1105227530', 'Pablo', 'Alejandro', 'Arevalo', 'Ramon', 'Clodoveo Jaramillo', 'pablo_alejandro1493@hotmail.com', '0939986543', 'M', '4MA'),
(75, 76, 'CED', '1104692080', 'Marisela', 'Alexandra', 'Valladolid', 'Herrera', 'Av. Eugenio Espejo y Adolfo Valarezo', 'maryalex1835@gmail.com', '0994251480', 'F', '4MA'),
(76, 77, 'CED', '1104430077', 'Carmen', 'Liliana', 'Jumbo', 'Ortiz', 'José Maria Peña e/ Rocafuerte y 10 de Agosto', 'lili20_1987@hotmail.com', '0983411404', 'F', '4MA');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `alumno_representanteconyuge`
--

DROP TABLE IF EXISTS `alumno_representanteconyuge`;
CREATE TABLE IF NOT EXISTS `alumno_representanteconyuge` (
  `conyuge_id` int NOT NULL AUTO_INCREMENT,
  `conyuge_repid` int NOT NULL,
  `conyuge_tipoidentificacion` varchar(3) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish2_ci NOT NULL,
  `conyuge_identificacion` varchar(13) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish2_ci NOT NULL,
  `conyuge_primernombre` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish2_ci NOT NULL,
  `conyuge_segundonombre` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish2_ci DEFAULT NULL,
  `conyuge_apellidopaterno` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish2_ci NOT NULL,
  `conyuge_apellidomaterno` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish2_ci DEFAULT NULL,
  `conyuge_direccion` varchar(400) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish2_ci NOT NULL,
  `conyuge_correo` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish2_ci NOT NULL,
  `conyuge_celular` varchar(10) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish2_ci NOT NULL,
  `conyuge_sexo` varchar(1) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish2_ci NOT NULL,
  PRIMARY KEY (`conyuge_id`),
  KEY `conyuge_repid` (`conyuge_repid`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_spanish2_ci;

--
-- Volcado de datos para la tabla `alumno_representanteconyuge`
--

INSERT INTO `alumno_representanteconyuge` (`conyuge_id`, `conyuge_repid`, `conyuge_tipoidentificacion`, `conyuge_identificacion`, `conyuge_primernombre`, `conyuge_segundonombre`, `conyuge_apellidopaterno`, `conyuge_apellidomaterno`, `conyuge_direccion`, `conyuge_correo`, `conyuge_celular`, `conyuge_sexo`) VALUES
(1, 1, 'CED', '1104015282', 'Verónica', 'Magali', 'Quinde', 'España', 'Rey David 410-34 y Juan el Bautista', 'vronik.quinde@gmail.com', '0982218969', 'F');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `asistencia_hora`
--

DROP TABLE IF EXISTS `asistencia_hora`;
CREATE TABLE IF NOT EXISTS `asistencia_hora` (
  `hora_id` int NOT NULL AUTO_INCREMENT,
  `hora_inicio` time NOT NULL,
  `hora_fin` time NOT NULL,
  `hora_detalle` varchar(300) NOT NULL,
  `hora_estado` char(1) DEFAULT NULL,
  PRIMARY KEY (`hora_id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `asistencia_hora`
--

INSERT INTO `asistencia_hora` (`hora_id`, `hora_inicio`, `hora_fin`, `hora_detalle`, `hora_estado`) VALUES
(1, '13:10:00', '14:10:00', 'Hora inicio', 'A'),
(2, '15:10:00', '16:00:00', 'Hora dos', 'A'),
(3, '16:20:00', '17:20:00', 'hora tres', 'A'),
(4, '17:20:00', '18:00:00', 'Hora fin', 'A');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `asistencia_lugar`
--

DROP TABLE IF EXISTS `asistencia_lugar`;
CREATE TABLE IF NOT EXISTS `asistencia_lugar` (
  `lugar_id` int NOT NULL AUTO_INCREMENT,
  `lugar_sedeid` int NOT NULL,
  `lugar_nombre` varchar(100) DEFAULT NULL,
  `lugar_direccion` varchar(300) DEFAULT NULL,
  `lugar_detalle` varchar(300) DEFAULT NULL,
  `lugar_estado` char(1) NOT NULL DEFAULT 'A',
  PRIMARY KEY (`lugar_id`),
  KEY `lugar_sedeid` (`lugar_sedeid`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `asistencia_lugar`
--

INSERT INTO `asistencia_lugar` (`lugar_id`, `lugar_sedeid`, `lugar_nombre`, `lugar_direccion`, `lugar_detalle`, `lugar_estado`) VALUES
(1, 1, 'Cancha los búfalos', 'Dirección', 'https://maps.app.goo.gl/rqhZMi2egExXy8NP6', 'A'),
(2, 1, 'Champios', 'Dirección', 'https://maps.app.goo.gl/e16Esh2jHcBzp2dG6', 'A');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `general_escuela`
--

DROP TABLE IF EXISTS `general_escuela`;
CREATE TABLE IF NOT EXISTS `general_escuela` (
  `escuela_id` int NOT NULL AUTO_INCREMENT,
  `escuela_ruc` varchar(20) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish2_ci NOT NULL,
  `escuela_nombre` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish2_ci NOT NULL,
  `escuela_direccion` varchar(200) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish2_ci NOT NULL,
  `escuela_email` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish2_ci NOT NULL,
  `escuela_telefono` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish2_ci DEFAULT NULL,
  `escuela_movil` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish2_ci NOT NULL,
  `escuela_logo` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish2_ci DEFAULT NULL,
  `escuela_recibo` int NOT NULL,
  `escuela_pension` decimal(10,2) NOT NULL,
  `escuela_inscripcion` decimal(10,2) NOT NULL,
  PRIMARY KEY (`escuela_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_spanish2_ci;

--
-- Volcado de datos para la tabla `general_escuela`
--

INSERT INTO `general_escuela` (`escuela_id`, `escuela_ruc`, `escuela_nombre`, `escuela_direccion`, `escuela_email`, `escuela_telefono`, `escuela_movil`, `escuela_logo`, `escuela_recibo`, `escuela_pension`, `escuela_inscripcion`) VALUES
(1, '1103533913001', 'Escuela IDV Loja', 'Daniel Álvarez, Av. Eloy Alfaro y Porfirio Díaz Esq.', 'escuelaidvloja@gmail.com', '0993911650', '0993911650', '1103533913001_34.jpg', 88, 30.00, 20.00);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `general_sede`
--

DROP TABLE IF EXISTS `general_sede`;
CREATE TABLE IF NOT EXISTS `general_sede` (
  `sede_id` int NOT NULL AUTO_INCREMENT,
  `sede_escuelaid` int NOT NULL,
  `sede_nombre` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish2_ci NOT NULL,
  `sede_direccion` varchar(200) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish2_ci NOT NULL,
  `sede_email` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish2_ci NOT NULL,
  `sede_telefono` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish2_ci DEFAULT NULL,
  PRIMARY KEY (`sede_id`),
  KEY `sede_escuelaid` (`sede_escuelaid`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_spanish2_ci;

--
-- Volcado de datos para la tabla `general_sede`
--

INSERT INTO `general_sede` (`sede_id`, `sede_escuelaid`, `sede_nombre`, `sede_direccion`, `sede_email`, `sede_telefono`) VALUES
(1, 1, 'IDV LOJA', 'Daniel Álvarez, Av. Eloy Alfaro y Porfirio Díaz Esq.', 'escueldaidvloja@gmail.com', '0993911650'),
(2, 1, 'IDV CATAMAYO', 'CATAMAYO, Juan José Peña y Luis Colmenar', 'idvcatam@mail.com', '0985623147');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `general_tabla`
--

DROP TABLE IF EXISTS `general_tabla`;
CREATE TABLE IF NOT EXISTS `general_tabla` (
  `tabla_id` int NOT NULL AUTO_INCREMENT,
  `tabla_nombre` varchar(30) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish2_ci NOT NULL,
  `tabla_estado` bit(1) NOT NULL,
  PRIMARY KEY (`tabla_id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_spanish2_ci;

--
-- Volcado de datos para la tabla `general_tabla`
--

INSERT INTO `general_tabla` (`tabla_id`, `tabla_nombre`, `tabla_estado`) VALUES
(1, 'tipo_documento', b'1'),
(2, 'nacionalidad', b'1'),
(3, 'posicion_juego', b'1'),
(4, 'parentesco', b'1'),
(5, 'rubros', b'1'),
(6, 'forma_pago', b'1'),
(7, 'descuento', b'1');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `general_tabla_catalogo`
--

DROP TABLE IF EXISTS `general_tabla_catalogo`;
CREATE TABLE IF NOT EXISTS `general_tabla_catalogo` (
  `catalogo_valor` varchar(3) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish2_ci NOT NULL,
  `catalogo_tablaid` int NOT NULL,
  `catalogo_descripcion` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish2_ci NOT NULL,
  `catalogo_estado` bit(1) NOT NULL,
  PRIMARY KEY (`catalogo_valor`),
  KEY `catalogo_tablaid` (`catalogo_tablaid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_spanish2_ci;

--
-- Volcado de datos para la tabla `general_tabla_catalogo`
--

INSERT INTO `general_tabla_catalogo` (`catalogo_valor`, `catalogo_tablaid`, `catalogo_descripcion`, `catalogo_estado`) VALUES
('CED', 1, 'CÉDULA', b'1'),
('PAS', 1, 'PASAPORTE', b'1'),
('DNI', 1, 'DNI', b'1'),
('ECU', 2, 'ECUATORIANA', b'1'),
('PER', 2, 'PERUANA', b'1'),
('COL', 2, 'COLOMBIANA', b'1'),
('VEN', 2, 'VENEZOLANA', b'1'),
('USA', 2, 'ESTADOUNIDENSE', b'1'),
('3DE', 3, 'Delantero', b'1'),
('3AR', 3, 'Portero', b'1'),
('3CE', 3, 'Centrocampista', b'1'),
('3DF', 3, 'Defensa', b'1'),
('4MA', 4, 'Madre', b'1'),
('4PA', 4, 'Padre', b'1'),
('4HE', 4, 'Hermano/a', b'1'),
('4TI', 4, 'Tio/a', b'1'),
('4AB', 4, 'Abuelo/a', b'1'),
('ROT', 5, 'Otros', b'1'),
('RKE', 5, 'Kit entrenamiento', b'1'),
('RIN', 5, 'Inscripción', b'1'),
('RPE', 5, 'Pensión', b'1'),
('RNU', 5, 'Nuevo Uniforme', b'1'),
('FEF', 6, 'Efectivo', b'1'),
('FTR', 6, 'Transferencia', b'1'),
('FTC', 6, 'Tarjeta', b'1'),
('FJU', 6, 'Justificado', b'1'),
('DBC', 7, 'Beca', b'1'),
('DDS', 7, 'Descuento', b'1');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `seguridad_menu`
--

DROP TABLE IF EXISTS `seguridad_menu`;
CREATE TABLE IF NOT EXISTS `seguridad_menu` (
  `menu_id` int NOT NULL AUTO_INCREMENT,
  `menu_nombre` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish2_ci NOT NULL,
  `menu_orden` int NOT NULL,
  `menu_padre` int NOT NULL,
  `menu_hijo` bit(1) NOT NULL,
  `menu_vista` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish2_ci NOT NULL,
  `menu_icono` varchar(20) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish2_ci NOT NULL,
  `menu_activo` bit(1) NOT NULL DEFAULT b'1',
  PRIMARY KEY (`menu_id`),
  KEY `menu_padre` (`menu_padre`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_spanish2_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `seguridad_permiso`
--

DROP TABLE IF EXISTS `seguridad_permiso`;
CREATE TABLE IF NOT EXISTS `seguridad_permiso` (
  `permiso_id` int NOT NULL AUTO_INCREMENT,
  `permiso_rolid` int NOT NULL,
  `permiso_menuid` int NOT NULL,
  `permiso_activo` bit(1) NOT NULL,
  PRIMARY KEY (`permiso_id`),
  KEY `permiso_rolid` (`permiso_rolid`),
  KEY `permiso_menuid` (`permiso_menuid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_spanish2_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `seguridad_rol`
--

DROP TABLE IF EXISTS `seguridad_rol`;
CREATE TABLE IF NOT EXISTS `seguridad_rol` (
  `rol_id` int NOT NULL AUTO_INCREMENT,
  `rol_nombre` varchar(20) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish2_ci NOT NULL,
  `rol_detalle` varchar(300) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish2_ci DEFAULT NULL,
  `rol_fechacreacion` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `rol_fechaactualizacion` datetime DEFAULT NULL,
  `rol_eliminado` bit(1) NOT NULL DEFAULT b'0',
  `rol_activo` bit(1) NOT NULL DEFAULT b'1',
  PRIMARY KEY (`rol_id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_spanish2_ci;

--
-- Volcado de datos para la tabla `seguridad_rol`
--

INSERT INTO `seguridad_rol` (`rol_id`, `rol_nombre`, `rol_detalle`, `rol_fechacreacion`, `rol_fechaactualizacion`, `rol_eliminado`, `rol_activo`) VALUES
(1, 'Super Administrador', 'super administrador', '2024-04-21 10:57:59', '0000-00-00 00:00:00', b'0', b'1'),
(2, 'Administrador', 'administrador del sisrema', '2024-04-21 10:58:11', '0000-00-00 00:00:00', b'0', b'1');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `seguridad_usuario`
--

DROP TABLE IF EXISTS `seguridad_usuario`;
CREATE TABLE IF NOT EXISTS `seguridad_usuario` (
  `usuario_id` int NOT NULL AUTO_INCREMENT,
  `usuario_usuario` varchar(20) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish2_ci NOT NULL,
  `usuario_rolid` int NOT NULL,
  `usuario_clave` varchar(200) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish2_ci NOT NULL,
  `usuario_nombre` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish2_ci DEFAULT NULL,
  `usuario_email` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish2_ci DEFAULT NULL,
  `usuario_movil` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish2_ci DEFAULT NULL,
  `usuario_fechacreacion` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `usuario_fechacambioclave` datetime DEFAULT NULL,
  `usuario_fechaactualizado` datetime DEFAULT NULL,
  `usuario_cambiaclave` bit(1) NOT NULL DEFAULT b'0',
  `usuario_tienebloqueo` bit(1) NOT NULL DEFAULT b'0',
  `usuario_activo` bit(1) NOT NULL DEFAULT b'1',
  `usuario_eliminado` bit(1) NOT NULL DEFAULT b'0',
  `usuario_imagen` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish2_ci DEFAULT NULL,
  PRIMARY KEY (`usuario_id`),
  UNIQUE KEY `usuario_usuario` (`usuario_usuario`),
  UNIQUE KEY `usuario_email` (`usuario_email`),
  UNIQUE KEY `usuario_movil` (`usuario_movil`),
  KEY `usuario_rolid` (`usuario_rolid`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_spanish2_ci;

--
-- Volcado de datos para la tabla `seguridad_usuario`
--

INSERT INTO `seguridad_usuario` (`usuario_id`, `usuario_usuario`, `usuario_rolid`, `usuario_clave`, `usuario_nombre`, `usuario_email`, `usuario_movil`, `usuario_fechacreacion`, `usuario_fechacambioclave`, `usuario_fechaactualizado`, `usuario_cambiaclave`, `usuario_tienebloqueo`, `usuario_activo`, `usuario_eliminado`, `usuario_imagen`) VALUES
(1, 'idvadminloja', 1, '$2y$10$F0J8k.lFjgGAK6I/tcbhyuMKSaitXy8ENMSBVZWErIoA6.VSU8MQy', 'Adminstrador del sistema', 'mail@mail.com', '9898989898', '2024-04-21 11:06:38', '0000-00-00 00:00:00', '2024-04-25 12:12:55', b'0', b'0', b'1', b'0', ''),
(2, 'fbpinzon', 2, '$2y$10$f9Douwb5IJ/tUmP/0ENZVO8mVDX9A4ZPj.7sk20w2k3EB4eQ8oTX6', 'Freddy Pinzón Olmedo', 'fb@mail.om', '0993120984', '2024-04-24 22:30:21', NULL, '2024-06-13 22:17:47', b'0', b'0', b'0', b'0', 'fbpinzon_37.jpg'),
(3, 'jdalvarez', 2, '$2y$10$D68bzcRdj.c.TDuc2rCWsOlnJMvDS5zoe6G/ziQbiXUf0NkOyJBXO', 'Jennyfer Daniela Álvarez Castro', 'jdalvarezcastro@gmail.com', '0996344221', '2024-05-21 19:48:50', NULL, '2024-05-21 19:48:50', b'0', b'0', b'1', b'0', '');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `seguridad_usuario_sede`
--

DROP TABLE IF EXISTS `seguridad_usuario_sede`;
CREATE TABLE IF NOT EXISTS `seguridad_usuario_sede` (
  `usuariosede_usuarioid` int NOT NULL,
  `usuariosede_sedeid` int NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_spanish2_ci;

--
-- Volcado de datos para la tabla `seguridad_usuario_sede`
--

INSERT INTO `seguridad_usuario_sede` (`usuariosede_usuarioid`, `usuariosede_sedeid`) VALUES
(1, 1),
(2, 1),
(3, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sujeto_alumno`
--

DROP TABLE IF EXISTS `sujeto_alumno`;
CREATE TABLE IF NOT EXISTS `sujeto_alumno` (
  `alumno_id` int NOT NULL AUTO_INCREMENT,
  `alumno_sedeid` int NOT NULL,
  `alumno_posicionid` varchar(3) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish2_ci DEFAULT NULL,
  `alumno_nacionalidadid` varchar(3) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish2_ci DEFAULT NULL,
  `alumno_tipoidentificacion` varchar(3) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish2_ci NOT NULL,
  `alumno_identificacion` varchar(20) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish2_ci NOT NULL,
  `alumno_primernombre` varchar(150) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish2_ci NOT NULL,
  `alumno_segundonombre` varchar(150) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish2_ci DEFAULT NULL,
  `alumno_apellidopaterno` varchar(150) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish2_ci NOT NULL,
  `alumno_apellidomaterno` varchar(150) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish2_ci DEFAULT NULL,
  `alumno_nombrecorto` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish2_ci DEFAULT NULL,
  `alumno_direccion` varchar(400) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish2_ci DEFAULT NULL,
  `alumno_fechanacimiento` date NOT NULL,
  `alumno_fechaingreso` date DEFAULT NULL,
  `alumno_genero` char(1) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish2_ci DEFAULT NULL,
  `alumno_hermanos` char(1) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish2_ci DEFAULT NULL,
  `alumno_activo` char(1) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish2_ci DEFAULT NULL,
  `alumno_imagen` varchar(300) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish2_ci DEFAULT NULL,
  `alumno_numcamiseta` int DEFAULT NULL,
  `alumno_cedulaA` varchar(20) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish2_ci DEFAULT NULL,
  `alumno_cedulaR` varchar(20) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish2_ci DEFAULT NULL,
  PRIMARY KEY (`alumno_id`),
  KEY `alumno_sedeid` (`alumno_sedeid`),
  KEY `alumno_posicionid` (`alumno_posicionid`),
  KEY `alumno_nacionalidadid` (`alumno_nacionalidadid`),
  KEY `alumno_tipoidentificacion` (`alumno_tipoidentificacion`)
) ENGINE=MyISAM AUTO_INCREMENT=78 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_spanish2_ci;

--
-- Volcado de datos para la tabla `sujeto_alumno`
--

INSERT INTO `sujeto_alumno` (`alumno_id`, `alumno_sedeid`, `alumno_posicionid`, `alumno_nacionalidadid`, `alumno_tipoidentificacion`, `alumno_identificacion`, `alumno_primernombre`, `alumno_segundonombre`, `alumno_apellidopaterno`, `alumno_apellidomaterno`, `alumno_nombrecorto`, `alumno_direccion`, `alumno_fechanacimiento`, `alumno_fechaingreso`, `alumno_genero`, `alumno_hermanos`, `alumno_activo`, `alumno_imagen`, `alumno_numcamiseta`, `alumno_cedulaA`, `alumno_cedulaR`) VALUES
(1, 1, '3AR', 'ECU', 'CED', '1150859807', 'Matias', 'Ezequiel', 'Pinzon', 'Quinde', 'Mati', 'Rey David 410-34 y Juan el Bautista', '2014-02-20', '2018-08-08', 'M', 'S', 'S', '1150859807_824.jpg', 1, NULL, NULL),
(2, 1, '3DE', 'ECU', 'CED', '1150271888', 'Jostin', 'Alexis', 'Manchay', 'Castillo', 'Jostin', 'Cdla Daniel Álvarez', '2012-06-16', '2024-04-01', 'M', 'N', 'S', '', 0, NULL, NULL),
(3, 1, '3DE', 'ECU', 'CED', '1150360558', 'Mathias', 'Alejandro', 'Ocampo', 'Zaruma', '', 'Colón e/ Sucre y 18 de noviembre', '2012-08-29', '2022-05-10', 'M', 'S', 'S', '', 0, NULL, NULL),
(4, 1, '3DE', 'ECU', 'CED', '1151197801', 'Cristhian', 'Vinicio', 'Ocampo', 'Zaruma', '', 'Colón e/ Sucre y 18 de Noviembre', '2016-10-24', '2022-05-10', 'M', 'S', 'S', '', 0, NULL, NULL),
(5, 1, '3DE', 'ECU', 'CED', '1151089271', 'Danna', 'Estefania', 'Rodriguez', 'Alba', '', 'Ciudad Alegría, Av. Condamine', '2015-09-01', '2024-01-01', 'F', 'S', 'S', '', 0, NULL, NULL),
(6, 1, '3DE', 'ECU', 'CED', '1150856100', 'Matias', 'Alejandro', 'Poma', 'Pesantez', '', 'Azuay y Sucre', '2014-02-28', '2023-11-06', 'M', 'N', 'S', '', 0, NULL, NULL),
(7, 1, '3DE', 'ECU', 'CED', '1105732760', 'Samiel', 'Katari', 'Gualán', 'Medina', '', 'Colón y Lauro Guerrero', '2006-10-04', '2024-05-29', 'M', 'N', 'S', '', 0, NULL, NULL),
(8, 1, '3DE', 'ECU', 'CED', '1150748646', 'Jeanluca', '', 'Alvarez', 'Castro', '', 'Cdla. Daniel Álvarez, Av. Eloy Alfaro y Porfirio Díaz', '2013-09-13', '2020-01-01', 'M', 'S', 'S', '', 0, NULL, NULL),
(9, 1, '3DE', 'ECU', 'CED', '1103533913', 'Carlos', 'Sebastián', 'Córdova', 'Luzón', '', 'Zarzas II', '2013-03-16', '2024-04-01', 'M', 'N', 'S', '', 0, '', ''),
(10, 1, '3DE', 'ECU', 'CED', '1150775706', 'Daniel', 'Alejandro', 'Villalta', 'Rosales', '', 'Operadores', '2013-11-09', '2023-09-26', 'M', 'S', 'S', '', 0, '1150775706A14.png', '1150775706R14.png'),
(11, 1, '3DE', 'ECU', 'CED', '1150530135', 'Nicolás', 'Sebastián', 'Villalta', 'Rosales', '', 'Operadores', '2012-10-29', '2023-09-26', 'M', 'S', 'S', '', 0, '', ''),
(12, 1, '3DE', 'ECU', 'CED', '1150418547', 'Jeremy', 'Israel', 'Guaicha', 'Jimenez', '', 'Unión Lojana', '2012-11-03', '2023-03-01', 'M', 'N', 'S', '', 0, '', ''),
(13, 1, '3DE', 'ECU', 'CED', '1754896981', 'Kevin', 'Wladimir', 'Roman', 'Erreyes', '', 'Daniel Álvarez, Francisco de Miranda y Miguel Hidalgo', '2010-06-29', '2024-06-03', 'M', 'N', 'S', '', 0, '', ''),
(14, 1, '3DE', 'ECU', 'CED', '1150304309', 'Fernando', 'Alonso', 'Cueva', 'Ordoñez', '', 'Los Geraneos', '2012-06-09', '2021-01-01', 'M', 'S', 'S', '', 0, '', ''),
(15, 1, '3DE', 'ECU', 'CED', '1151063631', 'Martin', 'Emilio', 'Cueva', 'Ordoñez', '', 'Los Geraneos', '2015-04-14', '2021-01-01', 'M', 'S', 'S', '', 0, '', ''),
(16, 1, '3DE', 'ECU', 'CED', '1150468070', 'Antonio', 'Jose', 'Maurad', 'Galarza', '', '', '2012-07-02', '2022-11-01', 'M', 'N', 'S', '', 0, '', ''),
(17, 1, '3DE', 'ECU', 'CED', '1150858288', 'Mathias', 'Gabriel', 'Maldonado', 'Hernandez', '', 'Barrio El Paraíso', '2014-03-05', '2023-09-01', 'M', 'S', 'S', '', 0, '', ''),
(18, 1, '3DE', 'ECU', 'CED', '1151356589', 'Maximiliano', 'Jose', 'Maldonado', 'Hernandez', '', 'Barrio El Paraíso', '2018-09-15', '2023-09-01', 'M', 'S', 'S', '', 0, '', ''),
(19, 1, '3DE', 'ECU', 'CED', '1151067814', 'Sebastián', 'Nicolás', 'Briceño', 'Gordillo', '', 'San Cayetano', '2014-09-11', '2024-06-04', 'M', 'N', 'S', '', 0, '', ''),
(20, 1, '3AR', 'ECU', 'CED', '1106237504', 'Rodrigo', 'Sebastián', 'Chacon', 'Coronado', '', 'Época', '2011-03-06', '2024-05-07', 'M', 'N', 'S', '', 0, '', ''),
(21, 1, '3DE', 'ECU', 'CED', '1151079439', 'Cristopher', 'Sebastián', 'Tillaguango', 'Ortega', '', 'Colinas Lojanas', '2015-04-20', '2024-04-01', 'M', 'N', 'S', '', 0, '', ''),
(22, 1, '3DE', 'ECU', 'CED', '1150867792', 'Joel', 'Martin', 'Sisalima', 'Jimenez', '', 'Av. Eugenio Espejo (entrada a Santa Ines)', '2014-03-24', '2024-06-04', 'M', 'N', 'S', '', 0, '', ''),
(23, 1, '3DE', 'ECU', 'CED', '1756222673', 'Dylan', 'Emanuel', 'Castillo', 'Romero', '', 'Punzara chico', '2014-08-26', '2024-04-01', 'M', 'N', 'S', '', 0, '', ''),
(24, 1, '3DE', 'ECU', 'CED', '1150680724', 'Matias', 'Alejandro', 'Naranjo', 'Angamarca', '', 'Turunuma alto', '2013-07-04', '2024-05-20', 'M', 'N', 'S', '', 0, '', ''),
(25, 1, '3DE', 'ECU', 'CED', '1151266549', 'Jorge', 'Luis', 'Lalangui', 'Chiliquinga', '', 'Sauces Norte, Salvador Dalí y Bustamante Celi', '2017-08-26', '2023-09-04', 'M', 'N', 'S', '', 0, '1151266549A0.jpg', ''),
(26, 1, '3DE', 'ECU', 'CED', '0962212965', 'Cristopher', 'Fabián', 'Veintimilla', 'Ostaíza', '', 'Dolorosa , Juan Bautista Aguirre y Jorge Icaza', '2018-02-09', '2024-05-01', 'M', 'N', 'S', '', 0, '', ''),
(27, 1, '3DE', 'ECU', 'CED', '1150960084', 'Angel', 'Andres', 'Serrano', 'Torres', '', 'Tierras Coloradas, Victor Emilio Valdiviezo', '2014-07-02', '2024-03-06', 'M', 'N', 'S', '', 0, '', ''),
(28, 1, '3DE', 'ECU', 'CED', '1151059282', 'Alvaro', 'Isaac', 'Hurtado', 'Rosales', '', 'Operadores', '2015-05-09', '2023-09-06', 'M', 'N', 'S', '', 0, '', ''),
(29, 1, '3DE', 'ECU', 'CED', '1105832545', 'Bryan', 'Vicente', 'Diaz', 'Poma', '', 'Julio Ordoñez', '2007-03-27', '2024-05-09', 'M', 'N', 'S', '', 0, '1105832545A28.jpg', ''),
(30, 1, '3DE', 'ECU', 'CED', '1151102181', 'Iker', 'Sebastián', 'Mejía', 'Sánchez', '', 'Daniel Álvarez', '2015-10-18', '2023-09-08', 'M', 'N', 'S', '', 0, '', ''),
(31, 1, '3DE', 'ECU', 'CED', '0961114410', 'Mathias', 'Leonardo', 'Sivizaca', 'Vera', '', 'Daniel Álvarez', '2016-10-26', '2024-01-01', 'M', 'N', 'S', '', 0, '0961114410A52.jpg', ''),
(32, 1, '3DE', 'ECU', 'CED', '1105429151', 'Luigi', 'Joel', 'Maldonado', 'Encarnación', '', 'El Pedestal', '2008-07-19', '2023-09-01', 'M', 'N', 'S', '', 0, '', ''),
(33, 1, '3DE', 'ECU', 'CED', '3050648504', 'Andres', '', 'Imaicela', 'Perez', '', 'Colinas Lojanas', '2017-11-01', '2024-03-01', 'M', 'N', 'S', '', 0, '', ''),
(34, 1, '3DE', 'ECU', 'CED', '1151342753', 'Cesar', 'Ricardo', 'Roldan', 'Bueno', '', 'Turunuma', '2018-07-18', '2024-03-04', 'M', 'N', 'S', '', 0, '', ''),
(35, 1, '3DE', 'ECU', 'CED', '1151348404', 'Jason', 'Alejandro', 'Chamba', 'Enriquez', '', 'Julio Jaramillo y Emer Vaca', '2018-08-06', '2024-05-01', 'M', 'N', 'S', '', 0, '', ''),
(36, 1, '3DE', 'ECU', 'CED', '1151212014', 'Martin', 'Raphael', 'Guaman', 'Beltrán', '', 'Sol de los Andes', '2016-12-23', '2024-04-04', 'M', 'N', 'S', '', 0, '', ''),
(37, 1, '3DE', 'ECU', 'CED', '1150364261', 'Erick', 'Alexander', 'Cabrera', 'Camacho', '', 'Daniel Álvarez', '2012-08-31', '2024-05-01', 'M', 'S', 'S', '', 0, '', ''),
(38, 1, '3DE', 'ECU', 'CED', '1151428057', 'Angel', 'Eduardo', 'Romero', 'Camacho', '', 'Daniel Álvarez', '2019-08-07', '2024-05-01', 'M', 'S', 'S', '', 0, '', ''),
(39, 1, '3DE', 'ECU', 'CED', '1151064332', 'Eduardo', 'Alfredo', 'Atarihuana', 'Yaguana', '', 'Portugal y Filipinas', '2015-05-30', '2023-01-05', 'M', 'N', 'S', '', 0, '', ''),
(40, 1, '3DE', 'ECU', 'CED', '1151158894', 'Dominick', 'Sebastián', 'Acaro', 'Cuenca', '', 'Época', '2016-05-25', '2024-01-01', 'M', 'N', 'S', '', 0, '', ''),
(41, 1, '3DE', 'ECU', 'CED', '1151308457', 'Emilio', 'Nicolás', 'Carranza', 'Viñan', '', 'Portugal e/ Brasil y Quebec', '2018-02-23', '2023-01-01', 'M', 'N', 'S', '', 0, '', ''),
(42, 1, '3DE', 'ECU', 'CED', '1151022603', 'Antony', 'Sebastián', 'Mendosa', 'Jaramillo', '', 'Época, Estados Unidos y Alemania', '2014-12-04', '2024-01-22', 'M', 'N', 'S', '', 0, '', ''),
(43, 1, '3DE', 'ECU', 'CED', '1151297296', 'Matheo', 'Alejandro', 'Malla', 'Matailo', '', 'Barrio Belén', '2018-01-06', '2023-10-02', 'M', 'N', 'S', '', 0, '1151297296A95.jpg', '1151297296R95.jpg'),
(44, 1, '3DE', 'ECU', 'CED', '1151036876', 'Emily', 'Carolina', 'Roman', 'Carrasco', '', 'Barrio Daniel Alvarez', '2015-01-23', '2024-05-01', 'F', 'S', 'S', '', 0, '1151036876A79.jpg', '1151036876R79.jpg'),
(45, 1, '3DE', 'ECU', 'CED', '1151466818', 'Mateo', 'Alejandro', 'Roman', 'Carrasco', '', 'Barrio Daniel Alvarez', '2020-02-05', '2024-05-01', 'M', 'S', 'S', '', 0, '1151466818A96.jpg', '1151466818R96.jpg'),
(46, 1, '3DE', 'ECU', 'CED', '1150855615', 'Jose', 'David', 'Naranjo', 'Guevara', '', 'Chontacruz', '2014-02-05', '2023-02-01', 'M', 'N', 'S', '', 0, '1150855615A14.jpg', '1150855615R14.jpg'),
(47, 1, '3DE', 'ECU', 'CED', '1150843868', 'Dylan', 'Paul', 'Gonzalez', 'Briones', '', 'Turunuma alto', '2014-01-02', '2023-06-06', 'M', 'N', 'S', '', 0, '1150843868A27.jpg', ''),
(48, 1, '3DE', 'ECU', 'CED', '1150515656', 'Freddy', 'Damian', 'Espejo', 'Carrión', '', 'El Rosal', '2013-03-07', '2022-09-05', 'M', 'N', 'S', '', 0, '1150515656A88.jpg', '1150515656R88.jpg'),
(49, 1, '3DE', 'ECU', 'CED', '1151322532', 'Paul', 'Ezequiel', 'Cumbicus', 'Rojas', '', 'Canteras del Pinar', '2018-04-25', '2024-06-07', 'F', 'N', 'S', '', 0, '', ''),
(50, 1, '3DE', 'ECU', 'CED', '1150751863', 'Diego', 'Alejandro', 'Encalada', 'Diaz', '', 'Colinas Lojanas', '2013-05-26', '2024-06-03', 'F', 'N', 'S', '', 0, '', ''),
(51, 1, '3DE', 'ECU', 'CED', '1151222583', 'Joan', 'Ismael', 'Carpio', 'Cano', '', 'Daniel Álvarez, Domingo Sarmiento y Jose de Artigas', '2017-02-10', '2024-04-02', 'M', 'N', 'S', '', 0, '1151222583A21.jpg', '1151222583R21.jpg'),
(52, 1, '3DE', 'ECU', 'CED', '1150536926', 'Enderson', 'Hernan', 'Jimenez', 'Sacon', '', 'Lote Bonito', '2009-01-19', '2024-05-07', 'M', 'S', 'S', '', 0, '1150536926A65.jpg', '1150536926R65.jpg'),
(53, 1, '3DE', 'ECU', 'CED', '1150536918', 'Jordy', 'Joel', 'Jimenez', 'Sacon', '', 'Lote Bonito', '2010-07-07', '2024-05-07', 'M', 'S', 'S', '', 0, '1150536918A98.jpg', '1150536918R98.jpg'),
(54, 1, '3DE', 'ECU', 'CED', '1151162029', 'Carlos', 'David', 'Sanchez', 'García', '', 'Sol de los Andes', '2016-05-24', '2023-03-20', 'M', 'N', 'S', '', 0, '1151162029_A41.jpg', '1151162029_R41.jpg'),
(55, 1, '3DE', 'ECU', 'CED', '1150213716', 'Mateo', 'Fernando', 'Jaramillo', 'Jacome', '', 'El Peñón', '2012-12-02', '2024-02-06', 'M', 'N', 'S', '', 0, '', ''),
(56, 1, '3DE', 'ECU', 'CED', '1151147830', 'Thiago', 'Sebastian', 'Campoverde', 'Vacacela', '', 'Av. Manuel Carrión Pinzano y Rocafuerte', '2016-03-21', '2023-11-01', 'M', 'N', 'S', '', 0, '', ''),
(57, 1, '3DE', 'ECU', 'CED', '1151203096', 'Wilson', 'Snaider', 'Lozano', 'Pardo', '', 'Juan José Samaniego', '2016-11-05', '2023-01-01', 'M', 'N', 'S', '', 0, '', ''),
(58, 1, '3DE', 'ECU', 'CED', '1150527743', 'Omar', 'Emanuel', 'Torres', 'Quezada', '', 'Época, Brasil y Honduras', '2012-01-13', '2024-05-01', 'M', 'N', 'S', '', 0, '', ''),
(59, 1, '3DE', 'ECU', 'CED', '0750752040', 'Laura', 'Sofia', 'Mejia', 'Rojas', '', 'Ciudad Alegría', '2013-10-12', '2023-03-01', 'M', 'S', 'S', '', 0, '', ''),
(60, 1, '3DE', 'ECU', 'CED', '0751396037', 'Angel', 'Enrique', 'Mejia', 'Rojas', '', 'Ciudad Alegría', '2016-09-03', '2023-03-01', 'M', 'S', 'S', '', 0, '', ''),
(61, 1, '3DE', 'ECU', 'CED', '1151036041', 'Eduardo', 'Jose', 'Celi', 'Arévalo', '', 'Esteban Godoy', '2015-02-02', '2024-04-01', 'M', 'N', 'S', '', 0, '1151036041_A26.jpg', ''),
(62, 1, '3DE', 'ECU', 'CED', '1150228144', 'Jhoel', 'Andres', 'Garcia', 'Soto', '', 'La Peñas, Mercadillo', '2008-10-24', '2024-02-20', 'M', 'N', 'S', '', 0, '', ''),
(63, 1, '3DE', 'ECU', 'CED', '1150688958', 'Marco', 'Antonio', 'Feijoo', 'Velepucha', '', 'Avenida de los Paltas y Herminia Jaramillo 30-53, Ciudadela Época.', '2013-07-11', '2024-04-01', 'M', 'S', 'S', '', 0, '1150688958A98.jpg', '1150688958R98.jpg'),
(64, 1, '3DE', 'ECU', 'CED', '1150983748', 'Eduardo', 'Nicolás', 'Feijoo', 'Velepucha', '', 'Avenida de los Paltas y Herminia Jaramillo 30-53, Ciudadela Época.', '2014-08-24', '2024-04-01', 'M', 'S', 'S', '', 0, '1150983748A7.jpg', '1150983748R7.jpg'),
(65, 1, '3DE', 'ECU', 'CED', '1151159157', 'Cristopher', 'Alejandro', 'Chuquimarca', 'Jimenez', '', 'Hernán Cortez y Porfirio Díaz', '2016-05-30', '2024-05-09', 'M', 'N', 'S', '', 0, '', ''),
(66, 1, '3DE', 'ECU', 'CED', '1151582085', 'Evans', 'Alejandro', 'Tamayo', 'Vargas', '', 'Operadores', '2021-09-11', '2024-04-02', 'M', 'S', 'S', '', 0, '', ''),
(67, 1, '3DE', 'ECU', 'CED', '1151423587', 'Jorge', 'Gael', 'Tamayo', 'Vargas', '', 'Operadores', '2019-07-07', '2024-04-02', 'M', 'S', 'S', '', 0, '', ''),
(68, 1, '3AR', 'ECU', 'CED', '1150255915', 'Luis', 'Javier', 'Morocho', 'Enriquez', '', 'Daniel Álvarez', '2012-06-04', '2024-03-07', 'M', 'N', 'S', '', 0, '1150255915_A47.jpg', '1150255915_R47.jpg'),
(69, 1, '3DE', 'ECU', 'CED', '1151195771', 'Dylan', 'Sneyder', 'Tarupi', 'Jimenez', '', 'Av. Isidro Ayora y Puebla (A media cuadra de la gasolinera de cooperativa Loja)', '2016-10-14', '2024-05-06', 'M', 'N', 'S', '', 0, '', ''),
(70, 1, '3DE', 'ECU', 'CED', '1151217534', 'Gabriel', 'Alejandro', 'Castillo', 'Jimenez', '', 'Sol de los Andes', '2017-01-18', '2024-01-02', 'M', 'N', 'S', '', 0, '', ''),
(71, 1, '3DE', 'ECU', 'CED', '1151136841', 'Santiago', 'David', 'Cueva', 'Ludeña', '', 'Cuero y Caicedo', '2016-02-18', '2024-03-01', 'M', 'S', 'S', '', 0, '', ''),
(72, 1, '3DE', 'ECU', 'CED', '1106090168', 'Xavier', 'Francisco', 'Cueva', 'Ludeña', '', 'Cuero y Caicedo', '2011-04-14', '2024-03-01', 'M', 'S', 'S', '', 0, '', ''),
(73, 1, '3DE', 'ECU', 'CED', '1151345657', 'Rafael', 'Eduardo', 'Reyes', 'Ordoñez', '', 'Bolívar e Imbabura', '2018-06-30', '2024-04-01', 'M', 'N', 'S', '', 0, '', ''),
(74, 1, '3DE', 'ECU', 'CED', '1151082789', 'Derek', 'Emanuel', 'Salinas', 'Romero', '', 'Sabiango e/ Lourdes y  Leopoldo Palacios', '2015-08-07', '2023-11-07', 'M', 'N', 'S', '', 0, '', ''),
(75, 1, '3DE', 'ECU', 'CED', '1151210455', 'Jeampierre', 'Alejandro', 'Arevalo', 'Soto', '', 'Clodoveo Jaramillo', '2016-12-15', '2024-05-09', 'M', 'N', 'S', '', 0, '', ''),
(76, 1, '3DE', 'ECU', 'CED', '0707147195', 'Jennifer', 'Alexandra', 'Camacho', 'Valladolid', '', 'Av. Eugenio Espejo y Adolfo Valarezo', '2012-10-03', '2024-06-10', 'F', 'N', 'S', '', 0, '', ''),
(77, 1, '3DE', 'ECU', 'CED', '1754969499', 'Jeremy', 'Leonel', 'Cuenca', 'Jumbo', '', 'José Maria Peña e/ Rocafuerte y 10 de Agosto', '2013-12-27', '2024-06-10', 'M', 'N', 'S', '', 0, '', '');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
