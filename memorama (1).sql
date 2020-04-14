-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 14-04-2020 a las 02:10:55
-- Versión del servidor: 10.3.16-MariaDB
-- Versión de PHP: 7.3.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `memorama`
--
CREATE DATABASE IF NOT EXISTS `memorama` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `memorama`;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `materias`
--

CREATE TABLE `materias` (
  `id` int(11) NOT NULL,
  `nombre` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `materias`
--

INSERT INTO `materias` (`id`, `nombre`) VALUES
(1, 'filosofia cuantica'),
(2, 'Semat');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `parejas`
--

CREATE TABLE `parejas` (
  `id` int(11) NOT NULL,
  `idmateria` int(11) NOT NULL,
  `concepto` varchar(100) NOT NULL,
  `descripcion` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `parejas`
--

INSERT INTO `parejas` (`id`, `idmateria`, `concepto`, `descripcion`) VALUES
(1, 1, 'Quantum decoherence', 'The loss of quantum coherence.'),
(2, 1, 'Superposition principle', 'State for all linear systems, the net response caused by two or more stimuli is the sum of the responses that would have been caused by each stimulus individually.'),
(3, 1, 'Born Law', 'Probabily that a measurement on a quantum system will yield a given result.'),
(4, 1, 'Measurement', 'Testing of manipulation of a physical system in order to yield a numerical result.'),
(5, 1, 'Agnosticism', 'The view of the existence of God.'),
(6, 1, 'Empiricism', 'A theory that states that knowledge comes only or primarily from sensory experience.'),
(7, 1, 'Scientific realism', 'The view that the universe described by science is real regardless of how it may be interpreted.'),
(8, 1, 'Modal interpretations', 'A formarl approach to the philosophy of science.'),
(9, 1, 'Quantum logic', 'Set of rules of reasoning about prepositions that takes the principles of quantum theory into account.'),
(10, 2, 'Semat', 'Software Engineering Method and Theory.'),
(11, 2, 'Alphas', 'Representations of the essential things to work with.'),
(12, 2, 'Activity Spaces', 'Representations of the essential things to do.'),
(13, 2, 'Customer', 'Area of concern the team needs to understand the stakeholders and the opportunity to be addressed'),
(14, 2, 'Solution', 'Area of concern the team needs to establish a share understanding of the requirements, and implement, build, test, deploy and support a software system.'),
(15, 2, 'Endeavor', 'Area of concern the team and its way-of-working have to be formed, and the work has to be done.'),
(16, 2, 'Opportunity', 'The set of circumstances that makes it appropriate to develop or change a software system.'),
(17, 2, 'Stakeholders', 'The people, groups, or organizations who affect or are affected by a software system.'),
(18, 2, 'Requirements', 'What the software system must do to address the opportunity and satisfy the stakeholders.'),
(19, 2, 'Software System', 'A system made up of software, hardware, and data that provides its primary value by the execution of the software.'),
(20, 2, 'Work', 'Activity involving mental or physical effort done in order to achieve a result.'),
(21, 2, 'Team', 'The group of people actively engaged in the development, maintenance, delivery and support of a specific software system.'),
(22, 2, 'Way of work', 'The tailored set of practices and tools used by a team to guide and support their work.'),
(23, 2, 'Stakeholder Representation', 'This competency encapsulates the ability to gather, communicate, and balance the needs of other stakeholders, and accurately represent their views.'),
(24, 2, 'Analysis', 'This competency encapsulates the ability to understand opportunities and their related stakeholder needs, and transform them into an agreed upon and consistent set of  requirements.'),
(25, 2, 'Development', 'This competency encapsulates the ability to design and program effective software systems following the standards and norms agreed upon by the team.'),
(26, 2, 'Testing', 'This competency encapsulates the ability to test a system, verifying that it is usable and that it meets the requirements.'),
(27, 2, 'Leadership', 'This competency enable a person to inspire and motivate a group of people to achieve a successful conclusion to their work and to meet their objectives.'),
(28, 2, 'Management', 'This competency encapsulates the ability to coordinate, plan, and track the work done by a team.');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `puntajes`
--

CREATE TABLE `puntajes` (
  `id_usuario` int(11) NOT NULL,
  `id_materia` int(11) NOT NULL,
  `fecha` datetime NOT NULL,
  `dificultad` varchar(7) NOT NULL,
  `puntaje` bigint(20) NOT NULL,
  `parejas_encontradas` int(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `puntajes`
--

INSERT INTO `puntajes` (`id_usuario`, `id_materia`, `fecha`, `dificultad`, `puntaje`, `parejas_encontradas`) VALUES
(1, 1, '2020-02-15 06:02:01', 'dificil', 0, 0),
(1, 1, '2020-02-15 06:02:43', 'facil', -1, 9),
(1, 1, '2020-02-15 06:02:54', 'facil', 41, 9),
(1, 1, '2020-04-14 02:04:06', 'facil', 0, 0),
(1, 2, '2020-02-15 06:02:13', 'dificil', 0, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL DEFAULT 'usuario',
  `tipo` int(1) NOT NULL DEFAULT 0,
  `clave` varchar(100) NOT NULL DEFAULT 'memopass'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`id`, `nombre`, `tipo`, `clave`) VALUES
(1, 'pepe', 0, 'camello');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `materias`
--
ALTER TABLE `materias`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `parejas`
--
ALTER TABLE `parejas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idmateria_idx` (`idmateria`);

--
-- Indices de la tabla `puntajes`
--
ALTER TABLE `puntajes`
  ADD PRIMARY KEY (`id_materia`,`fecha`,`id_usuario`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `materias`
--
ALTER TABLE `materias`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `parejas`
--
ALTER TABLE `parejas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `parejas`
--
ALTER TABLE `parejas`
  ADD CONSTRAINT `idmateria` FOREIGN KEY (`idmateria`) REFERENCES `materias` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
