-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1:3306
-- Tiempo de generación: 26-05-2024 a las 17:16:22
-- Versión del servidor: 10.11.7-MariaDB-cll-lve
-- Versión de PHP: 7.2.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `u335636780_minimininds`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `groups`
--

CREATE TABLE `groups` (
  `group_id` int(11) NOT NULL,
  `group_name` varchar(100) NOT NULL,
  `group_code` varchar(20) NOT NULL,
  `descrip` text DEFAULT NULL,
  `created_by` varchar(100) DEFAULT NULL,
  `student_count` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `groups`
--

INSERT INTO `groups` (`group_id`, `group_name`, `group_code`, `descrip`, `created_by`, `student_count`, `created_at`, `updated_at`) VALUES
(14, 'Gryffindor', '140D6553', 'Gryffindor', 'Severus Snape', 3, '2024-05-14 03:12:44', '2024-05-14 03:12:44'),
(15, 'Slytherin', '1F3D13D6', 'Slytherin', 'Severus Snape', 1, '2024-05-16 04:50:04', '2024-05-16 04:50:04');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `juegos`
--

CREATE TABLE `juegos` (
  `juego_id` int(11) NOT NULL,
  `nombre_juego` varchar(255) NOT NULL,
  `url` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `juegos`
--

INSERT INTO `juegos` (`juego_id`, `nombre_juego`, `url`) VALUES
(1, 'BubblesGame', ''),
(2, 'Memorama', ''),
(3, 'QuizGame', ''),
(4, 'Uniendo Puntos', '');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `quizzes`
--

CREATE TABLE `quizzes` (
  `quiz_id` int(11) NOT NULL,
  `teacher_id` int(11) DEFAULT NULL,
  `quiz_name` varchar(100) DEFAULT NULL,
  `descrip` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `quiz_questions`
--

CREATE TABLE `quiz_questions` (
  `question_id` int(11) NOT NULL,
  `quiz_id` int(11) DEFAULT NULL,
  `question_text` text DEFAULT NULL,
  `answer_optionA` varchar(255) DEFAULT NULL,
  `answer_optionB` varchar(255) NOT NULL,
  `answer_optionC` varchar(255) NOT NULL,
  `answer_optionD` varchar(255) NOT NULL,
  `correct_answer` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `recursos`
--

CREATE TABLE `recursos` (
  `id` int(11) NOT NULL,
  `shared_by` int(11) DEFAULT NULL,
  `grupo` int(11) DEFAULT NULL,
  `nombre` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `students`
--

CREATE TABLE `students` (
  `id` int(11) NOT NULL,
  `grupo` varchar(20) NOT NULL,
  `profile_picture` varchar(255) NOT NULL,
  `nombre_completo` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `nombre_tutor` varchar(255) NOT NULL,
  `telefono_tutor` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `students`
--

INSERT INTO `students` (`id`, `grupo`, `profile_picture`, `nombre_completo`, `email`, `nombre_tutor`, `telefono_tutor`) VALUES
(51, 'Gryffindor', 'hermione.jpg', 'Hermione Granger', 'Hg@gmail.com', '', '3310458876'),
(52, 'Gryffindor', 'RonWeasley.jpg', 'Ron Weasley', 'ronW@gmail.com', 'Molly Weasley', '3310458876'),
(53, 'Gryffindor', 'harrypotter.jpg', 'Harry Potter', 'harry@gmail.com', 'Sirius Black', '3310458876'),
(57, '', 'lunalovegood.jpg', 'Luna Lovegood', 'lunalove12@gmail.com', 'Sr. Lovegood', ''),
(58, '', 'cedric.jpg', 'Cedric Diggory', 'cedric@gmail.com', '', ''),
(60, 'Slytherin', 'draco.jpg', 'Draco Malfoy', 'malfoy@gmail.com', '', ''),
(61, '', 'Ginny.jpg', 'Ginny Weasley', 'gw@gmail.com', '', ''),
(63, '', '1000078197.jpg', '20300075', 'j@gmail.com', '', '');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `student_interactions`
--

CREATE TABLE `student_interactions` (
  `interaction_id` int(11) NOT NULL,
  `student_id` int(11) DEFAULT NULL,
  `task_id` int(11) DEFAULT NULL,
  `start_time` timestamp NOT NULL DEFAULT current_timestamp(),
  `end_time` timestamp NULL DEFAULT NULL,
  `duration` varchar(100) DEFAULT NULL,
  `puntaje` double DEFAULT NULL,
  `detalles` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `student_interactions`
--

INSERT INTO `student_interactions` (`interaction_id`, `student_id`, `task_id`, `start_time`, `end_time`, `duration`, `puntaje`, `detalles`) VALUES
(4, 53, 37, '2024-05-16 04:38:06', '2024-05-16 04:38:06', '00:20', NULL, '9 movimientos'),
(5, 51, 37, '2024-05-17 01:10:00', '2024-05-17 01:10:00', '00:30', NULL, '10 movimientos'),
(6, 60, 41, '2024-05-17 06:03:29', '2024-05-17 06:03:29', '00:14', NULL, '8 movimientos'),
(12, 51, 39, '2024-05-22 19:50:02', '2024-05-22 19:50:02', '00:21', NULL, '10 movimientos'),
(13, 52, 44, '2024-05-23 01:52:57', '2024-05-23 01:52:57', '00:27', 7, '87 puntos de 100'),
(18, 60, 43, '2024-05-25 14:44:26', '2024-05-25 14:44:26', '50 s', 9, '64.3 puntos de 100'),
(19, 52, 45, '2024-05-25 14:46:17', '2024-05-25 14:46:17', '50 s', 18, '90.0 puntos de 100'),
(20, 51, 45, '2024-05-25 15:09:29', '2024-05-25 15:09:29', '50 s', 12, '92.3 puntos de 100'),
(24, 51, 47, '2024-05-26 16:21:55', '2024-05-26 16:21:55', '00:08', NULL, 'Figura realizada: star');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tasks`
--

CREATE TABLE `tasks` (
  `task_id` int(11) NOT NULL,
  `class_id` int(11) DEFAULT NULL,
  `task_name` varchar(255) DEFAULT NULL,
  `descripcion` text DEFAULT NULL,
  `juego_id` int(11) DEFAULT NULL,
  `created_by` varchar(255) NOT NULL,
  `configuracion` text NOT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp(),
  `fecha_expiracion` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tasks`
--

INSERT INTO `tasks` (`task_id`, `class_id`, `task_name`, `descripcion`, `juego_id`, `created_by`, `configuracion`, `fecha_creacion`, `fecha_expiracion`) VALUES
(37, 14, 'Memorama numeros', 'Memorama numeros', 2, 'Severus Snape', 'configuracion-memorama.json', '2024-05-16 02:43:19', '2024-05-18 18:43:00'),
(39, 14, 'Memorama Vocales', 'Memorama Vocales', 2, 'Severus Snape', 'configuracion-memorama.json', '2024-05-16 05:38:23', '2024-05-17 21:38:00'),
(41, 15, 'Memorama Vocales', 'Memorama Vocales', 2, 'Severus Snape', 'configuracion-memorama.json', '2024-05-16 12:50:39', '2024-05-19 04:50:00'),
(43, 15, 'Letra B', 'Letra B', 1, 'Severus Snape', 'configuracion-burbujas.json', '2024-05-20 23:36:10', '2024-05-25 22:40:00'),
(44, 14, 'Cuestionario 1', 'Cuestionario sobre números y matematicas', 3, 'Severus Snape', 'configuracion-quiz.json', '2024-05-21 04:58:56', '2024-05-25 23:59:00'),
(45, 14, 'Letra F', 'Identificar la letra F', 1, 'Severus Snape', 'configuracion-burbujas.json', '2024-05-21 05:01:59', '2024-05-30 23:59:00'),
(46, 15, 'Cuestionario Español', 'Cuestionario predeterminado de las vocales', 3, 'Severus Snape', 'configuracion-quiz.json', '2024-05-22 19:02:12', '2024-05-26 05:00:00'),
(47, 14, 'Secuencia de numeros', 'Sigue los puntos para completar la figura', 4, 'Severus Snape', 'configuracion-figurasP.json', '2024-05-25 23:48:42', '2024-05-30 23:59:00'),
(48, 15, 'Secuencia de numeros', 'Sigue los numeros y une los puntos para encontrar la figura', 4, 'Severus Snape', 'configuracion-figurasP.json', '2024-05-26 00:45:53', '2024-05-28 23:00:00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `teachers`
--

CREATE TABLE `teachers` (
  `id` int(11) NOT NULL,
  `profile_picture` varchar(255) NOT NULL,
  `nombre_completo` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `telefono` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `teachers`
--

INSERT INTO `teachers` (`id`, `profile_picture`, `nombre_completo`, `email`, `telefono`) VALUES
(54, 'severus.jpg', 'Severus Snape', 'ss@gmail.com', ''),
(59, 'albus.jpg', 'Albus Dumbledore', 'dumbledore@gmail.com', '');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `nombre_completo` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('student','teacher') NOT NULL,
  `profile_picture` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id`, `nombre_completo`, `email`, `password`, `role`, `profile_picture`) VALUES
(51, 'Hermione Granger', 'Hg@gmail.com', '1234', 'student', 'hermione.jpg'),
(52, 'Ron Weasley', 'ronW@gmail.com', '1234', 'student', 'RonWeasley.jpg'),
(53, 'Harry Potter', 'harry@gmail.com', '1234', 'student', 'harrypotter.jpg'),
(54, 'Severus Snape', 'ss@gmail.com', '1234', 'teacher', 'severus.jpg'),
(57, 'Luna Lovegood', 'lunalove12@gmail.com', '1234', 'student', 'lunalovegood.jpg'),
(58, 'Cedric Diggory', 'cedric@gmail.com', '1234', 'student', 'cedric.jpg'),
(59, 'Albus Dumbledore', 'dumbledore@gmail.com', '1234', 'teacher', 'albus.jpg'),
(60, 'Draco Malfoy', 'malfoy@gmail.com', '1234', 'student', 'draco.jpg'),
(61, 'Ginny Weasley', 'gw@gmail.com', '1234', 'student', 'Ginny.jpg'),
(63, '20300075', 'j@gmail.com', '1234', 'student', '1000078197.jpg');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `groups`
--
ALTER TABLE `groups`
  ADD PRIMARY KEY (`group_id`);

--
-- Indices de la tabla `juegos`
--
ALTER TABLE `juegos`
  ADD PRIMARY KEY (`juego_id`);

--
-- Indices de la tabla `quizzes`
--
ALTER TABLE `quizzes`
  ADD PRIMARY KEY (`quiz_id`);

--
-- Indices de la tabla `quiz_questions`
--
ALTER TABLE `quiz_questions`
  ADD PRIMARY KEY (`question_id`),
  ADD KEY `quiz_id` (`quiz_id`);

--
-- Indices de la tabla `recursos`
--
ALTER TABLE `recursos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `shared_by` (`shared_by`),
  ADD KEY `grupo` (`grupo`);

--
-- Indices de la tabla `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `student_interactions`
--
ALTER TABLE `student_interactions`
  ADD PRIMARY KEY (`interaction_id`),
  ADD KEY `student_id` (`student_id`),
  ADD KEY `task_id` (`task_id`);

--
-- Indices de la tabla `tasks`
--
ALTER TABLE `tasks`
  ADD PRIMARY KEY (`task_id`),
  ADD KEY `juego_id` (`juego_id`),
  ADD KEY `tasks_ibfk_1` (`class_id`);

--
-- Indices de la tabla `teachers`
--
ALTER TABLE `teachers`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `groups`
--
ALTER TABLE `groups`
  MODIFY `group_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de la tabla `juegos`
--
ALTER TABLE `juegos`
  MODIFY `juego_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `quizzes`
--
ALTER TABLE `quizzes`
  MODIFY `quiz_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `quiz_questions`
--
ALTER TABLE `quiz_questions`
  MODIFY `question_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `recursos`
--
ALTER TABLE `recursos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `student_interactions`
--
ALTER TABLE `student_interactions`
  MODIFY `interaction_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT de la tabla `tasks`
--
ALTER TABLE `tasks`
  MODIFY `task_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=64;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `quiz_questions`
--
ALTER TABLE `quiz_questions`
  ADD CONSTRAINT `quiz_questions_ibfk_1` FOREIGN KEY (`quiz_id`) REFERENCES `quizzes` (`quiz_id`);

--
-- Filtros para la tabla `recursos`
--
ALTER TABLE `recursos`
  ADD CONSTRAINT `recursos_ibfk_1` FOREIGN KEY (`shared_by`) REFERENCES `teachers` (`id`),
  ADD CONSTRAINT `recursos_ibfk_2` FOREIGN KEY (`grupo`) REFERENCES `groups` (`group_id`);

--
-- Filtros para la tabla `students`
--
ALTER TABLE `students`
  ADD CONSTRAINT `students_ibfk_1` FOREIGN KEY (`id`) REFERENCES `users` (`id`);

--
-- Filtros para la tabla `student_interactions`
--
ALTER TABLE `student_interactions`
  ADD CONSTRAINT `student_interactions_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`),
  ADD CONSTRAINT `student_interactions_ibfk_2` FOREIGN KEY (`task_id`) REFERENCES `tasks` (`task_id`);

--
-- Filtros para la tabla `tasks`
--
ALTER TABLE `tasks`
  ADD CONSTRAINT `tasks_ibfk_1` FOREIGN KEY (`class_id`) REFERENCES `groups` (`group_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `tasks_ibfk_2` FOREIGN KEY (`juego_id`) REFERENCES `juegos` (`juego_id`);

--
-- Filtros para la tabla `teachers`
--
ALTER TABLE `teachers`
  ADD CONSTRAINT `teachers_ibfk_1` FOREIGN KEY (`id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
