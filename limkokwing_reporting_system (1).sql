-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 06, 2024 at 08:07 AM
-- Server version: 10.4.19-MariaDB
-- PHP Version: 7.3.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `limkokwing_reporting_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `academic_years`
--

CREATE TABLE `academic_years` (
  `id` int(11) NOT NULL,
  `year` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `academic_years`
--

INSERT INTO `academic_years` (`id`, `year`) VALUES
(3, '2023'),
(2, '2024'),
(10, '2025'),
(11, '2026');

-- --------------------------------------------------------

--
-- Table structure for table `classes`
--

CREATE TABLE `classes` (
  `id` int(11) NOT NULL,
  `class_name` varchar(50) NOT NULL,
  `current_year` varchar(4) NOT NULL,
  `semester` enum('S1','S2') NOT NULL,
  `number_of_students` int(11) DEFAULT 0,
  `class_code` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `classes`
--

INSERT INTO `classes` (`id`, `class_name`, `current_year`, `semester`, `number_of_students`, `class_code`) VALUES
(1, 'DIT', '', 'S1', 0, ''),
(7, 'DBIT', '2024', 'S1', 45, 'DBITY1S1'),
(8, 'Tourism', '2024', 'S1', 50, 'MRSF212'),
(10, 'DMSE', '<br ', 'S2', 50, 'DMSEY3S1');

-- --------------------------------------------------------

--
-- Table structure for table `lecturer_modules`
--

CREATE TABLE `lecturer_modules` (
  `id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `lecturer_id` int(11) NOT NULL,
  `module_id` int(11) NOT NULL,
  `class_id` int(11) NOT NULL,
  `semester_id` int(11) NOT NULL,
  `academic_year_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `lecturer_modules`
--

INSERT INTO `lecturer_modules` (`id`, `created_at`, `updated_at`, `lecturer_id`, `module_id`, `class_id`, `semester_id`, `academic_year_id`) VALUES
(20, '2024-11-03 18:20:10', '2024-11-03 18:20:10', 36, 5, 10, 1, 11),
(21, '2024-11-03 18:27:56', '2024-11-03 18:27:56', 35, 2, 1, 2, 10),
(22, '2024-11-03 18:51:44', '2024-11-03 18:51:44', 33, 3, 10, 1, 3);

-- --------------------------------------------------------

--
-- Table structure for table `lecturer_reports`
--

CREATE TABLE `lecturer_reports` (
  `id` int(11) NOT NULL,
  `lecturer_id` int(11) NOT NULL,
  `class_name` varchar(255) NOT NULL,
  `module_name` varchar(255) NOT NULL,
  `challenges` text NOT NULL,
  `recommendations` text NOT NULL,
  `report_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `lecturer_reports`
--

INSERT INTO `lecturer_reports` (`id`, `lecturer_id`, `class_name`, `module_name`, `challenges`, `recommendations`, `report_date`) VALUES
(1, 8, 'DIT', 'php', 'gggg', 'gggg', '2024-10-28 20:16:15'),
(2, 8, 'DIT', 'php', 'gggg', 'gggg', '2024-10-28 20:19:06'),
(3, 38, 'DIT', 'HTML', 'rkltyltyl', 'krltylytl', '2024-11-04 17:43:13'),
(4, 38, 'Tourism', 'c', 'rkltyltyl', 'krltylytl', '2024-11-04 17:44:31'),
(5, 38, 'DMSE', 'java', 'slow learners', 'beat them', '2024-11-04 17:52:05'),
(6, 38, 'DMSE', 'java', 'slow learners', 'beat them', '2024-11-04 17:54:45'),
(7, 38, 'DIT', 'c', 'slow learners', 'beat them', '2024-11-04 17:55:24'),
(8, 38, 'DMSE', 'HTML', 'slow learners', 'beat them', '2024-11-04 18:51:52');

-- --------------------------------------------------------

--
-- Table structure for table `lecturer_roles`
--

CREATE TABLE `lecturer_roles` (
  `id` int(11) NOT NULL,
  `lecturer_id` int(11) NOT NULL,
  `class_id` int(11) NOT NULL,
  `present_students` varchar(255) NOT NULL,
  `chapter` varchar(255) NOT NULL,
  `learning_outcomes` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `class_name` varchar(255) NOT NULL,
  `lecturer_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `login_logs`
--

CREATE TABLE `login_logs` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `login_time` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `login_logs`
--

INSERT INTO `login_logs` (`id`, `username`, `login_time`) VALUES
(1, 'Thapelo1', '2024-10-14 17:44:05'),
(2, 'Thapelo1', '2024-10-14 17:57:58'),
(3, 'Thapelo1', '2024-10-14 18:50:43'),
(4, 'Thapelo1', '2024-10-14 18:54:33'),
(5, 'Thapelo1', '2024-10-15 06:31:17'),
(6, 'Thapelo2', '2024-10-15 06:32:04'),
(7, 'Thapelo3', '2024-10-15 06:36:25'),
(8, 'Thapelo3', '2024-10-15 06:51:06'),
(9, 'Thapelo2', '2024-10-15 06:51:22'),
(10, 'Thapelo2', '2024-10-15 07:08:24'),
(11, 'Thapelo2', '2024-10-15 07:10:35'),
(12, 'Thapelo2', '2024-10-16 08:32:21'),
(13, 'Thapelo2', '2024-10-16 08:36:27'),
(14, 'Thapelo2', '2024-10-16 08:42:04'),
(15, 'Thapelo2', '2024-10-16 09:06:02'),
(16, 'Thapelo2', '2024-10-16 09:46:05'),
(17, 'Thapelo2', '2024-10-16 10:05:44'),
(18, 'Thapelo2', '2024-10-16 12:20:19'),
(19, 'Thapelo2', '2024-10-22 18:37:46'),
(20, 'Thapelo2', '2024-10-22 19:13:54'),
(21, 'Thapelo2', '2024-10-22 19:27:06'),
(22, 'khang', '2024-10-22 19:35:33'),
(23, 'Thapelo2', '2024-10-22 19:35:53'),
(24, 'khang', '2024-10-22 20:03:48'),
(25, 'Thapelo2', '2024-10-22 20:04:03'),
(26, 'khang', '2024-10-22 20:09:33'),
(27, 'Thapelo2', '2024-10-22 20:09:47'),
(28, 'Thapelo2', '2024-10-22 22:04:05'),
(29, 'Thapelo2', '2024-10-23 05:30:44'),
(30, 'Thapelo2', '2024-10-23 05:41:19'),
(31, 'chomathe', '2024-10-23 06:11:43'),
(32, 'Thapelo2', '2024-10-23 06:47:16'),
(33, 'chomathe', '2024-10-23 08:33:02'),
(34, 'Thapelo2', '2024-10-23 08:33:19'),
(35, 'Thapelo2', '2024-10-23 08:39:02'),
(36, 'Thapelo2', '2024-10-23 08:50:38'),
(37, 'Thapelo2', '2024-10-23 08:55:59'),
(38, 'Thapelo2', '2024-10-23 09:29:29'),
(39, 'Thapelo3', '2024-10-23 09:55:29'),
(40, 'Thapelo3', '2024-10-23 10:24:05'),
(41, 'Thapelo3', '2024-10-23 12:17:25'),
(42, 'Thapelo3', '2024-10-23 18:24:30'),
(43, 'Thapelo3', '2024-10-23 19:05:49'),
(44, 'Thapelo2', '2024-10-23 19:05:58'),
(45, 'Thapelo2', '2024-10-23 19:08:27'),
(46, 'Thapelo2', '2024-10-23 19:11:41'),
(47, 'Thapelo2', '2024-10-23 19:25:00'),
(48, 'Thapelo3', '2024-10-23 19:29:14'),
(49, 'Thapelo1', '2024-10-23 19:29:30'),
(50, 'Thapelo3', '2024-10-23 19:29:40'),
(51, 'Thapelo1', '2024-10-23 20:01:44'),
(52, 'Thapelo2', '2024-10-25 17:40:38'),
(53, 'Thapelo2', '2024-10-25 18:08:28'),
(54, 'chomathe', '2024-10-27 18:38:28'),
(55, 'Thapelo2', '2024-10-27 18:38:43'),
(56, 'chomathe', '2024-10-27 18:41:07'),
(57, 'chomathe', '2024-10-27 18:41:19'),
(58, 'Thapelo2', '2024-10-27 18:41:25'),
(59, 'Thapelo2', '2024-10-27 19:23:52'),
(60, 'Thapelo2', '2024-10-28 13:15:49'),
(61, 'Thapelo2', '2024-10-28 13:26:59'),
(62, 'Thapelo2', '2024-10-28 13:28:03'),
(63, 'Thapelo3', '2024-10-28 13:28:13'),
(64, 'Thapelo3', '2024-10-28 13:31:42'),
(65, 'chomathe', '2024-10-28 13:35:30'),
(66, 'chomathe', '2024-10-28 13:56:32'),
(67, 'Thapelo2', '2024-10-28 13:56:40'),
(68, 'chomathe', '2024-10-28 13:56:58'),
(69, 'Thapelo2', '2024-10-28 17:01:29'),
(70, 'chomathe', '2024-10-28 17:49:32'),
(71, 'Thapelo2', '2024-10-28 17:51:35'),
(72, 'chomathe', '2024-10-28 18:43:05'),
(73, 'Thapelo2', '2024-10-28 19:51:14'),
(74, 'Thapelo3', '2024-10-28 19:51:38'),
(75, 'Thapelo1', '2024-10-28 19:53:51'),
(76, 'Thapelo3', '2024-10-28 19:59:57'),
(77, 'Thapelo3', '2024-10-28 20:15:56'),
(78, 'Thapelo1', '2024-10-28 20:19:55'),
(79, 'Thapelo2', '2024-10-28 20:20:14'),
(80, 'Thapelo3', '2024-10-28 20:20:25'),
(81, 'Thapelo2', '2024-10-28 20:37:58'),
(82, 'Thapelo1', '2024-10-29 09:21:52'),
(83, 'Thapelo2', '2024-10-29 09:22:32'),
(84, 'Thapelo2', '2024-10-29 09:32:24'),
(85, 'Thapelo2', '2024-11-01 17:48:09'),
(86, 'Thapelo2', '2024-11-01 18:05:35'),
(87, 'Thapelo1', '2024-11-01 18:05:50'),
(88, 'Thapelo1', '2024-11-01 18:39:07'),
(89, 'Thapelo2', '2024-11-01 18:41:30'),
(90, 'Thapelo2', '2024-11-01 18:51:46'),
(91, 'Thapelo1', '2024-11-01 19:17:54'),
(92, 'Thapelo1', '2024-11-01 19:29:41'),
(93, 'Thapelo1', '2024-11-01 19:39:34'),
(94, 'Thapelo1', '2024-11-01 19:39:47'),
(95, 'Thapelo1', '2024-11-01 19:41:32'),
(96, 'Thapelo2', '2024-11-01 19:42:37'),
(97, 'chomathe', '2024-11-01 19:47:21'),
(98, 'Thapelo2', '2024-11-01 19:47:38'),
(99, 'Thapelo1', '2024-11-01 19:47:46'),
(100, 'chomathe', '2024-11-01 19:47:57'),
(101, 'Thapelo2', '2024-11-01 19:48:04'),
(102, 'Thapelo2', '2024-11-03 17:24:20'),
(103, 'montseng', '2024-11-03 17:40:06'),
(104, 'Thapelo2', '2024-11-03 17:40:24'),
(105, 'Thapelo2', '2024-11-03 19:18:16'),
(106, 'Thapelo2', '2024-11-03 19:58:04'),
(107, 'Thapelo2', '2024-11-03 20:02:42'),
(108, 'Thapelo2', '2024-11-04 14:08:44'),
(109, 'Thapelo2', '2024-11-04 14:37:49'),
(110, 'Thapelo2', '2024-11-04 14:53:35'),
(111, 'Tseliso', '2024-11-04 14:57:50'),
(112, 'Thapelo2', '2024-11-04 15:06:20'),
(113, 'Thapelo1', '2024-11-04 15:11:39'),
(114, 'Thapelo1', '2024-11-04 15:14:23'),
(115, 'Thapelo1', '2024-11-04 15:18:17'),
(116, 'Thapelo1', '2024-11-04 15:25:16'),
(117, 'Thapelo1', '2024-11-04 15:32:51'),
(118, 'Thapelo2', '2024-11-04 15:48:45'),
(119, 'Thapelo1', '2024-11-04 15:48:56'),
(120, 'Thapelo2', '2024-11-04 15:49:19'),
(121, 'Thapelo2', '2024-11-04 16:23:30'),
(122, 'Thapelo2', '2024-11-04 16:26:02'),
(123, 'Thapelo1', '2024-11-04 16:28:17'),
(124, 'Thapelo1', '2024-11-04 16:29:42'),
(125, 'Thapelo2', '2024-11-04 16:29:54'),
(126, 'chomathe', '2024-11-04 16:37:43'),
(127, 'Thapelo1', '2024-11-04 16:37:54'),
(128, 'Thapelo2', '2024-11-04 16:38:02'),
(129, 'Thapelo2', '2024-11-04 16:46:44'),
(130, 'Thapelo2', '2024-11-04 16:51:40'),
(131, 'Thapelo1', '2024-11-04 16:52:18'),
(132, 'Thapelo1', '2024-11-04 16:57:32'),
(133, 'Thapelo1', '2024-11-04 17:02:57'),
(134, 'Thapelo3', '2024-11-04 17:41:27'),
(135, 'Thapelo3', '2024-11-04 17:42:05'),
(136, 'Thapelo2', '2024-11-04 17:45:15'),
(137, 'Thapelo3', '2024-11-04 17:45:50'),
(138, 'Thapelo3', '2024-11-04 17:47:39'),
(139, 'Thapelo2', '2024-11-04 17:55:56'),
(140, 'Thapelo1', '2024-11-04 17:56:48'),
(141, 'Thapelo1', '2024-11-04 17:57:43'),
(142, 'Thapelo1', '2024-11-04 18:01:33'),
(143, 'Thapelo1', '2024-11-04 18:15:43'),
(144, 'Thapelo1', '2024-11-04 18:17:03'),
(145, 'Thapelo1', '2024-11-04 18:17:35'),
(146, 'Thapelo1', '2024-11-04 18:18:01'),
(147, 'Thapelo2', '2024-11-04 18:18:21'),
(148, 'Thapelo3', '2024-11-04 18:18:32'),
(149, 'Thapelo3', '2024-11-04 18:26:28'),
(150, 'Thapelo3', '2024-11-04 18:28:21'),
(151, 'Thapelo1', '2024-11-04 18:29:01'),
(152, 'Thapelo2', '2024-11-04 18:29:07'),
(153, 'Thapelo3', '2024-11-04 18:29:40'),
(154, 'chomathe', '2024-11-04 18:44:21'),
(155, 'Thapelo1', '2024-11-04 18:47:39'),
(156, 'Thapelo2', '2024-11-04 18:48:05'),
(157, 'Thapelo3', '2024-11-04 18:48:53'),
(158, 'Thapelo1', '2024-11-04 18:50:27'),
(159, 'Thapelo2', '2024-11-04 18:50:49'),
(160, 'Thapelo3', '2024-11-04 18:51:31'),
(161, 'Thapelo1', '2024-11-05 05:36:46'),
(162, 'Thapelo1', '2024-11-05 05:40:02'),
(163, 'Thapelo2', '2024-11-05 05:40:17'),
(164, 'Thapelo3', '2024-11-05 05:42:01'),
(165, 'Thapelo1', '2024-11-05 18:57:42'),
(166, 'Thapelo2', '2024-11-05 18:58:12'),
(167, 'Thapelo3', '2024-11-05 18:58:57'),
(168, 'Thapelo3', '2024-11-06 06:13:08'),
(169, 'Thapelo2', '2024-11-06 06:15:41');

-- --------------------------------------------------------

--
-- Table structure for table `modules`
--

CREATE TABLE `modules` (
  `id` int(11) NOT NULL,
  `module_name` varchar(100) NOT NULL,
  `module_code` varchar(50) NOT NULL,
  `description` text DEFAULT NULL,
  `lecturer_id` int(11) DEFAULT NULL,
  `academic_year` varchar(10) NOT NULL,
  `semester` enum('Semester 1','Semester 2') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `modules`
--

INSERT INTO `modules` (`id`, `module_name`, `module_code`, `description`, `lecturer_id`, `academic_year`, `semester`, `created_at`, `updated_at`) VALUES
(1, 'java', '', NULL, NULL, '', 'Semester 1', '2024-10-16 10:08:35', '2024-10-16 10:08:35'),
(2, 'php', 'fimg3113', NULL, NULL, '2', 'Semester 1', '2024-10-22 20:32:54', '2024-10-22 20:32:54'),
(3, 'HTML', 'jhdgii334', NULL, NULL, '2024', 'Semester 1', '2024-10-22 20:53:10', '2024-10-22 20:53:10'),
(4, 'C++', 'FN3112', NULL, NULL, '2023', 'Semester 1', '2024-10-27 18:43:14', '2024-10-27 18:43:14'),
(5, 'c', 'HGT3112', NULL, NULL, '2025', 'Semester 2', '2024-11-03 17:42:16', '2024-11-03 17:42:16');

-- --------------------------------------------------------

--
-- Table structure for table `reports`
--

CREATE TABLE `reports` (
  `id` int(11) NOT NULL,
  `lecturer_id` int(11) DEFAULT NULL,
  `class_id` int(11) DEFAULT NULL,
  `module_id` int(11) DEFAULT NULL,
  `challenges` text DEFAULT NULL,
  `recommendations` text DEFAULT NULL,
  `report_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `semesters`
--

CREATE TABLE `semesters` (
  `id` int(11) NOT NULL,
  `semester` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `semesters`
--

INSERT INTO `semesters` (`id`, `semester`) VALUES
(2, '1'),
(1, '2');

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `student_number` varchar(50) NOT NULL,
  `date_of_birth` date NOT NULL,
  `academic_year` varchar(50) NOT NULL,
  `semester` enum('Semester 1','Semester 2') NOT NULL,
  `email` varchar(255) NOT NULL,
  `contacts` varchar(50) NOT NULL,
  `class_name` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`id`, `name`, `student_number`, `date_of_birth`, `academic_year`, `semester`, `email`, `contacts`, `class_name`) VALUES
(1, 'Thapelo Sekolo', '901016633', '2003-04-25', '2023', 'Semester 1', 'thapelosekolo@gmail.com', '58527094', 'DIT'),
(2, 'Thapelo Sekolo', '901016633', '2003-04-25', '2023', 'Semester 1', 'thapelosekolo@gmail.com', '58527094', 'DIT'),
(3, 'Tumelo Khang', '90101717', '2024-10-15', '2023', 'Semester 1', 'tumelo@gmail.com', '56784909', 'DIT'),
(4, 'Tumelo Khang', '90101717', '2024-10-15', '2023', 'Semester 1', 'tumelo@gmail.com', '56784909', 'DIT'),
(5, 'Tumelo Khang', '90101717', '2024-10-10', '2023', 'Semester 2', 'tumelo@gmail.com', '56784909', 'DIT'),
(6, 'Tumelo Khang', '90101717', '2024-10-10', '2023', 'Semester 2', 'tumelo@gmail.com', '56784909', 'DIT');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `surname` varchar(100) NOT NULL,
  `gender` enum('male','female') NOT NULL,
  `maiden_name` varchar(100) DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `contact` varchar(15) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','lecturer','prl') NOT NULL,
  `employee_number` varchar(100) NOT NULL,
  `registration_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `surname`, `gender`, `maiden_name`, `email`, `contact`, `username`, `password`, `role`, `employee_number`, `registration_date`) VALUES
(6, 'Thapelo', 'Sekolo', 'male', NULL, 'thapelo@gmail.com', '58527094', 'Thapelo2', '$2y$10$d1WfpQIjQd.So6R.iYVkg.QV3dqlg8hxYVkvYm2lt/spSztHYyTbK', 'admin', 'EMP9429', '2024-10-14 18:56:13'),
(28, 'Tumelo', 'Khang', 'male', '', 'tumelo@gmail.com', '56784909', 'khang', '$2y$10$awt9cQIEh0I.LhobFNA/Nuym5j2kr8Dp7thUsKLOTc.6ZnykPvStO', 'lecturer', 'EMP6395', '2024-10-22 20:09:03'),
(33, 'chomathe', 'Sekolo', 'female', 'mothakathi', 'chomathesekolo@gmail.com', '58554748', 'chomathe', '$2y$10$JNE5OXOlZpsewVpL/oARFeOplv0DInaiJ7pxQCykllzoWdoJ7bLTa', 'lecturer', 'EMP5369', '2024-10-27 18:39:54'),
(35, 'Thapelo', 'Sekolo', 'male', NULL, 'thapelosekolo@gmail.com', '58527094', 'Thapelo1', '$2y$10$48QPLsJSubRBPmNUWYbOBOCwHpOxQZsMoyf3XMqNoQYxrwp1UOjLu', 'lecturer', 'EMP8358', '2024-10-28 19:53:35'),
(36, 'Montseng', 'Sekolo', 'male', '', 'montseng@gmail.com', '58554747', 'montseng', '$2y$10$DHfk/EFqRAlcF20aiYgkJegHxVAM1lXOn70oKb.rpGKMl7H5Pm6C.', 'lecturer', 'EMP1050', '2024-11-03 17:39:11'),
(37, 'Tseliso', 'Sekolo', 'male', '', 'tseliso@gmail.com', '56611622', 'Tseliso', '$2y$10$aPbIhQJ28wvnETMmBFj2bOZIPPXoDa9AwmOrXViQgGIBjgbqZUM2y', 'lecturer', 'EMP1504', '2024-11-04 14:56:22'),
(38, 'Thapelo', 'Sekolo', 'male', NULL, 'sekolo@gmail.com', '58527094', 'Thapelo3', '$2y$10$Dcj7ySTRga59pmDYOqhrd.9/oYfVFqonPRQ6D16os0QHUrn4wrj9W', 'prl', 'EMP5418', '2024-11-04 17:37:37'),
(39, 'Thato', 'Khoale', 'male', '', 'thato@gmail.com', '50000000', 'thato', '$2y$10$ROy/9SALRuZ7QdmFb0pcsuGA1IBv9VtTNHz4ltcd.l62RpDcBymp.', 'lecturer', 'EMP9767', '2024-11-06 06:51:48');

-- --------------------------------------------------------

--
-- Table structure for table `weekly_reports`
--

CREATE TABLE `weekly_reports` (
  `id` int(11) NOT NULL,
  `lecturer_id` int(11) NOT NULL,
  `class_id` int(11) NOT NULL,
  `module_id` int(11) NOT NULL,
  `week` int(11) NOT NULL,
  `challenges` text NOT NULL,
  `recommendations` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `academic_years`
--
ALTER TABLE `academic_years`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `year` (`year`);

--
-- Indexes for table `classes`
--
ALTER TABLE `classes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `class_name` (`class_name`);

--
-- Indexes for table `lecturer_modules`
--
ALTER TABLE `lecturer_modules`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `lecturer_reports`
--
ALTER TABLE `lecturer_reports`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `lecturer_roles`
--
ALTER TABLE `lecturer_roles`
  ADD PRIMARY KEY (`id`),
  ADD KEY `lecturer_id` (`lecturer_id`),
  ADD KEY `class_id` (`class_id`);

--
-- Indexes for table `login_logs`
--
ALTER TABLE `login_logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `modules`
--
ALTER TABLE `modules`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `module_code` (`module_code`),
  ADD KEY `lecturer_id` (`lecturer_id`);

--
-- Indexes for table `reports`
--
ALTER TABLE `reports`
  ADD PRIMARY KEY (`id`),
  ADD KEY `lecturer_id` (`lecturer_id`),
  ADD KEY `class_id` (`class_id`),
  ADD KEY `module_id` (`module_id`);

--
-- Indexes for table `semesters`
--
ALTER TABLE `semesters`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `semester` (`semester`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `employee_number` (`employee_number`);

--
-- Indexes for table `weekly_reports`
--
ALTER TABLE `weekly_reports`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `academic_years`
--
ALTER TABLE `academic_years`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `classes`
--
ALTER TABLE `classes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `lecturer_modules`
--
ALTER TABLE `lecturer_modules`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `lecturer_reports`
--
ALTER TABLE `lecturer_reports`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `lecturer_roles`
--
ALTER TABLE `lecturer_roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `login_logs`
--
ALTER TABLE `login_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=170;

--
-- AUTO_INCREMENT for table `modules`
--
ALTER TABLE `modules`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `reports`
--
ALTER TABLE `reports`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `semesters`
--
ALTER TABLE `semesters`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `weekly_reports`
--
ALTER TABLE `weekly_reports`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `lecturer_roles`
--
ALTER TABLE `lecturer_roles`
  ADD CONSTRAINT `lecturer_roles_ibfk_1` FOREIGN KEY (`lecturer_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `lecturer_roles_ibfk_2` FOREIGN KEY (`class_id`) REFERENCES `classes` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `modules`
--
ALTER TABLE `modules`
  ADD CONSTRAINT `modules_ibfk_1` FOREIGN KEY (`lecturer_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `reports`
--
ALTER TABLE `reports`
  ADD CONSTRAINT `reports_ibfk_1` FOREIGN KEY (`lecturer_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `reports_ibfk_2` FOREIGN KEY (`class_id`) REFERENCES `classes` (`id`),
  ADD CONSTRAINT `reports_ibfk_3` FOREIGN KEY (`module_id`) REFERENCES `modules` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
