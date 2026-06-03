-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jun 03, 2026 at 04:01 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `student_management`
--

-- --------------------------------------------------------

--
-- Table structure for table `academic_years`
--

CREATE TABLE `academic_years` (
  `id` int(11) NOT NULL,
  `year_name` varchar(20) NOT NULL,
  `status` enum('active','inactive') DEFAULT 'inactive',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `academic_years`
--

INSERT INTO `academic_years` (`id`, `year_name`, `status`, `created_at`, `updated_at`) VALUES
(1, '2024-2025', 'active', '2026-05-27 12:04:37', '2026-06-03 10:18:45'),
(2, '2025-2026', 'inactive', '2026-05-27 12:04:37', '2026-06-03 10:18:38'),
(3, '2026-2027', 'inactive', '2026-05-27 12:04:37', '2026-06-03 10:18:45');

-- --------------------------------------------------------

--
-- Table structure for table `activity_logs`
--

CREATE TABLE `activity_logs` (
  `id` int(11) NOT NULL,
  `admin_id` int(11) NOT NULL,
  `action` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `entity_type` varchar(50) DEFAULT NULL,
  `entity_id` int(11) DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `activity_logs`
--

INSERT INTO `activity_logs` (`id`, `admin_id`, `action`, `description`, `entity_type`, `entity_id`, `ip_address`, `created_at`) VALUES
(1, 1, 'LOGIN', 'Admin logged in', 'admin', 1, '::1', '2026-06-01 07:47:02'),
(2, 1, 'CREATE_STUDENT', 'Registered student: emmanuel usanase (STU-2026-0001)', 'student', 1, '::1', '2026-06-01 07:51:36'),
(3, 1, 'LOGOUT', 'Admin logged out', 'admin', 1, '::1', '2026-06-01 08:16:35'),
(4, 4, 'LOGIN', 'Admin logged in', 'admin', 4, '::1', '2026-06-01 08:16:46'),
(5, 4, 'LOGOUT', 'Admin logged out', 'admin', 4, '::1', '2026-06-01 08:16:57'),
(6, 1, 'LOGIN', 'Admin logged in', 'admin', 1, '::1', '2026-06-01 08:17:16'),
(7, 1, 'PROMOTE_STUDENT', 'Promoted student emmanuel usanase to class #10', 'student', 1, '::1', '2026-06-01 08:18:26'),
(8, 1, 'LOGOUT', 'Admin logged out', 'admin', 1, '::1', '2026-06-01 08:33:17'),
(9, 1, 'LOGIN', 'Admin logged in', 'admin', 1, '::1', '2026-06-01 11:31:23'),
(10, 1, 'LOGOUT', 'Admin logged out', 'admin', 1, '::1', '2026-06-01 11:31:44'),
(11, 4, 'LOGIN', 'Admin logged in', 'admin', 4, '::1', '2026-06-01 11:32:22'),
(12, 4, 'LOGIN', 'Admin logged in', 'admin', 4, '::1', '2026-06-02 10:02:05'),
(13, 4, 'LOGOUT', 'Admin logged out', 'admin', 4, '::1', '2026-06-02 10:17:01'),
(14, 1, 'LOGIN', 'Admin logged in', 'admin', 1, '::1', '2026-06-03 07:28:40'),
(15, 1, 'SET_ACTIVE_YEAR', 'Set academic year #3 as active', 'academic_year', 3, '::1', '2026-06-03 10:18:09'),
(16, 1, 'SET_ACTIVE_YEAR', 'Set academic year #1 as active', 'academic_year', 1, '::1', '2026-06-03 10:18:17'),
(17, 1, 'SET_ACTIVE_YEAR', 'Set academic year #2 as active', 'academic_year', 2, '::1', '2026-06-03 10:18:22'),
(18, 1, 'SET_ACTIVE_YEAR', 'Set academic year #3 as active', 'academic_year', 3, '::1', '2026-06-03 10:18:39'),
(19, 1, 'SET_ACTIVE_YEAR', 'Set academic year #1 as active', 'academic_year', 1, '::1', '2026-06-03 10:18:45'),
(20, 1, 'CREATE_STUDENT', 'Registered student: festus nawuyizere (STU-2026-0002)', 'student', 2, '::1', '2026-06-03 10:59:19'),
(21, 1, 'PROMOTE_STUDENT', 'Promoted student emmanuel usanase to class #23', 'student', 1, '::1', '2026-06-03 13:51:03'),
(22, 1, 'DELETE_STUDENT', 'Deleted student: festus nawuyizere', 'student', 2, '::1', '2026-06-03 13:51:29'),
(23, 1, 'DELETE_STUDENT', 'Deleted student: emmanuel usanase', 'student', 1, '::1', '2026-06-03 13:52:46'),
(24, 1, 'LOGOUT', 'Admin logged out', 'admin', 1, '::1', '2026-06-03 13:57:38'),
(25, 1, 'LOGIN', 'Admin logged in', 'admin', 1, '::1', '2026-06-03 13:58:56');

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `full_name` varchar(200) NOT NULL,
  `email` varchar(200) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `username`, `password`, `full_name`, `email`, `created_at`, `updated_at`) VALUES
(1, 'admin', '$2y$10$vHEng5LkNKLgwd7dB/QhJOGfYUpcudJv4PwVTGAEM7r.hel0W/H9i', 'System Administrator', 'admin@school.com', '2026-05-27 12:04:36', '2026-06-01 07:46:48'),
(4, 'bosco', '$2y$10$VoiGm2DiO6Mw3pLDHmKs7uw6P5Pgv9CqsqiUFlwwk88yYS2mFIC4u', 'Bosco', 'bosco@gsnyagisozi.ac.rw', '2026-06-01 08:07:38', '2026-06-01 08:07:38');

-- --------------------------------------------------------

--
-- Table structure for table `attendance`
--

CREATE TABLE `attendance` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `class_id` int(11) NOT NULL,
  `academic_year_id` int(11) NOT NULL,
  `attendance_date` date NOT NULL,
  `status` enum('present','absent','late','excused') NOT NULL DEFAULT 'present',
  `remarks` varchar(255) DEFAULT NULL,
  `recorded_by` int(11) NOT NULL COMMENT 'admin_id',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `classes`
--

CREATE TABLE `classes` (
  `id` int(11) NOT NULL,
  `class_name` varchar(50) NOT NULL,
  `level` varchar(50) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `classes`
--

INSERT INTO `classes` (`id`, `class_name`, `level`, `description`, `created_at`, `updated_at`) VALUES
(1, 'P1', 'Primary', NULL, '2026-05-27 12:04:37', '2026-05-27 12:04:37'),
(2, 'P2', 'Primary', NULL, '2026-05-27 12:04:37', '2026-05-27 12:04:37'),
(3, 'P3', 'Primary', NULL, '2026-05-27 12:04:37', '2026-05-27 12:04:37'),
(4, 'P4', 'Primary', NULL, '2026-05-27 12:04:37', '2026-05-27 12:04:37'),
(5, 'P5', 'Primary', NULL, '2026-05-27 12:04:37', '2026-05-27 12:04:37'),
(6, 'P6', 'Primary', NULL, '2026-05-27 12:04:37', '2026-05-27 12:04:37'),
(7, 'S1', 'Secondary', NULL, '2026-05-27 12:04:37', '2026-05-27 12:04:37'),
(8, 'S2', 'Secondary', NULL, '2026-05-27 12:04:37', '2026-05-27 12:04:37'),
(9, 'S3', 'Secondary', NULL, '2026-05-27 12:04:37', '2026-05-27 12:04:37'),
(10, 'S4', 'Secondary', NULL, '2026-05-27 12:04:37', '2026-05-27 12:04:37'),
(11, 'S5', 'Secondary', NULL, '2026-05-27 12:04:37', '2026-05-27 12:04:37'),
(12, 'S6', 'Secondary', NULL, '2026-05-27 12:04:37', '2026-05-27 12:04:37'),
(13, 'P1', 'Primary', NULL, '2026-06-01 07:46:48', '2026-06-01 07:46:48'),
(14, 'P2', 'Primary', NULL, '2026-06-01 07:46:48', '2026-06-01 07:46:48'),
(15, 'P3', 'Primary', NULL, '2026-06-01 07:46:48', '2026-06-01 07:46:48'),
(16, 'P4', 'Primary', NULL, '2026-06-01 07:46:48', '2026-06-01 07:46:48'),
(17, 'P5', 'Primary', NULL, '2026-06-01 07:46:48', '2026-06-01 07:46:48'),
(18, 'P6', 'Primary', NULL, '2026-06-01 07:46:48', '2026-06-01 07:46:48'),
(19, 'S1', 'Secondary', NULL, '2026-06-01 07:46:48', '2026-06-01 07:46:48'),
(20, 'S2', 'Secondary', NULL, '2026-06-01 07:46:48', '2026-06-01 07:46:48'),
(21, 'S3', 'Secondary', NULL, '2026-06-01 07:46:48', '2026-06-01 07:46:48'),
(22, 'S4', 'Secondary', NULL, '2026-06-01 07:46:48', '2026-06-01 07:46:48'),
(23, 'S5', 'Secondary', NULL, '2026-06-01 07:46:48', '2026-06-01 07:46:48'),
(24, 'S6', 'Secondary', NULL, '2026-06-01 07:46:48', '2026-06-01 07:46:48');

-- --------------------------------------------------------

--
-- Table structure for table `discipline_records`
--

CREATE TABLE `discipline_records` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `marks_removed` int(11) NOT NULL,
  `marks_before` int(11) NOT NULL,
  `marks_after` int(11) NOT NULL,
  `reason` text NOT NULL,
  `removed_by` varchar(200) NOT NULL COMMENT 'Name of person who removed marks',
  `admin_id` int(11) NOT NULL,
  `incident_date` date NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `id` int(11) NOT NULL,
  `registration_number` varchar(50) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `gender` enum('Male','Female','Other') NOT NULL,
  `date_of_birth` date NOT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `parent_name` varchar(200) DEFAULT NULL,
  `parent_phone` varchar(30) DEFAULT NULL,
  `guardian_name` varchar(200) DEFAULT NULL,
  `guardian_phone` varchar(30) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `village` varchar(100) DEFAULT NULL,
  `sector` varchar(100) DEFAULT NULL,
  `district` varchar(100) DEFAULT NULL,
  `email` varchar(200) DEFAULT NULL,
  `nationality` varchar(100) DEFAULT 'Rwandan',
  `admission_date` date NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`id`, `registration_number`, `first_name`, `last_name`, `gender`, `date_of_birth`, `photo`, `parent_name`, `parent_phone`, `guardian_name`, `guardian_phone`, `address`, `village`, `sector`, `district`, `email`, `nationality`, `admission_date`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 'STU-2026-0001', 'emmanuel', 'usanase', 'Male', '2008-09-28', '', 'mujyambere samuel', '0794068520', 'samweri ruhara', '0793848235', 'kigali', 'butembo', 'cyabakamyi', 'nyanza', 'whdreampro@gmail.com', 'Rwandan', '2026-06-01', '2026-06-03 13:52:46', '2026-06-01 07:51:36', '2026-06-03 13:52:46'),
(2, 'STU-2026-0002', 'festus', 'nawuyizere', 'Male', '2007-07-04', 'photo_6a2009077da70.jpeg', 'munyeshyaka samuel', '0794068524', 'cyaka murerwa', '0794068522', 'gicumbi / north', 'urumuri', 'icyizere', 'gicumbi', 'festus@gmail.com', 'Rwandan', '2026-06-03', NULL, '2026-06-03 10:59:19', '2026-06-03 13:51:41');

-- --------------------------------------------------------

--
-- Table structure for table `student_behavior_marks`
--

CREATE TABLE `student_behavior_marks` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `marks` int(11) NOT NULL DEFAULT 40 COMMENT 'Current balance, starts at 40',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `student_behavior_marks`
--

INSERT INTO `student_behavior_marks` (`id`, `student_id`, `marks`, `created_at`, `updated_at`) VALUES
(1, 1, 40, '2026-06-03 08:08:17', '2026-06-03 08:08:17'),
(2, 2, 40, '2026-06-03 10:59:19', '2026-06-03 10:59:19');

-- --------------------------------------------------------

--
-- Table structure for table `student_class_history`
--

CREATE TABLE `student_class_history` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `class_id` int(11) NOT NULL,
  `academic_year_id` int(11) NOT NULL,
  `status` enum('active','promoted','transferred','completed','repeated') DEFAULT 'active',
  `reason` enum('New Admission','Promotion','Transfer','Repeat') DEFAULT 'New Admission',
  `start_date` date NOT NULL,
  `end_date` date DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `student_class_history`
--

INSERT INTO `student_class_history` (`id`, `student_id`, `class_id`, `academic_year_id`, `status`, `reason`, `start_date`, `end_date`, `remarks`, `created_at`, `updated_at`) VALUES
(1, 1, 9, 2, 'promoted', 'New Admission', '2026-06-01', '2026-06-01', 'study in s3 B', '2026-06-01 07:51:36', '2026-06-01 08:18:26'),
(2, 1, 10, 3, 'promoted', 'Promotion', '2026-06-01', '2026-06-03', 'you have promoted right now', '2026-06-01 08:18:26', '2026-06-03 13:51:03'),
(3, 2, 20, 2, 'active', 'New Admission', '2026-06-03', NULL, 'nice one student ', '2026-06-03 10:59:19', '2026-06-03 10:59:19'),
(4, 1, 23, 3, 'active', 'Promotion', '2026-06-03', NULL, 'good luck!.', '2026-06-03 13:51:03', '2026-06-03 13:51:03');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `academic_years`
--
ALTER TABLE `academic_years`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `year_name` (`year_name`);

--
-- Indexes for table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `admin_id` (`admin_id`),
  ADD KEY `idx_action` (`action`),
  ADD KEY `idx_created` (`created_at`);

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `attendance`
--
ALTER TABLE `attendance`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_student_date` (`student_id`,`attendance_date`),
  ADD KEY `idx_class_date` (`class_id`,`attendance_date`),
  ADD KEY `idx_student` (`student_id`),
  ADD KEY `att_year_fk` (`academic_year_id`),
  ADD KEY `att_admin_fk` (`recorded_by`);

--
-- Indexes for table `classes`
--
ALTER TABLE `classes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `discipline_records`
--
ALTER TABLE `discipline_records`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_student` (`student_id`),
  ADD KEY `idx_date` (`incident_date`),
  ADD KEY `dr_admin_fk` (`admin_id`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `registration_number` (`registration_number`),
  ADD KEY `idx_registration` (`registration_number`),
  ADD KEY `idx_name` (`first_name`,`last_name`),
  ADD KEY `idx_deleted` (`deleted_at`);

--
-- Indexes for table `student_behavior_marks`
--
ALTER TABLE `student_behavior_marks`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_student` (`student_id`);

--
-- Indexes for table `student_class_history`
--
ALTER TABLE `student_class_history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `class_id` (`class_id`),
  ADD KEY `idx_student` (`student_id`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_year` (`academic_year_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `academic_years`
--
ALTER TABLE `academic_years`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `activity_logs`
--
ALTER TABLE `activity_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `attendance`
--
ALTER TABLE `attendance`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `classes`
--
ALTER TABLE `classes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `discipline_records`
--
ALTER TABLE `discipline_records`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `student_behavior_marks`
--
ALTER TABLE `student_behavior_marks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `student_class_history`
--
ALTER TABLE `student_class_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD CONSTRAINT `activity_logs_ibfk_1` FOREIGN KEY (`admin_id`) REFERENCES `admins` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `attendance`
--
ALTER TABLE `attendance`
  ADD CONSTRAINT `att_admin_fk` FOREIGN KEY (`recorded_by`) REFERENCES `admins` (`id`),
  ADD CONSTRAINT `att_class_fk` FOREIGN KEY (`class_id`) REFERENCES `classes` (`id`),
  ADD CONSTRAINT `att_student_fk` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `att_year_fk` FOREIGN KEY (`academic_year_id`) REFERENCES `academic_years` (`id`);

--
-- Constraints for table `discipline_records`
--
ALTER TABLE `discipline_records`
  ADD CONSTRAINT `dr_admin_fk` FOREIGN KEY (`admin_id`) REFERENCES `admins` (`id`),
  ADD CONSTRAINT `dr_student_fk` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `student_behavior_marks`
--
ALTER TABLE `student_behavior_marks`
  ADD CONSTRAINT `sbm_student_fk` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `student_class_history`
--
ALTER TABLE `student_class_history`
  ADD CONSTRAINT `student_class_history_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `student_class_history_ibfk_2` FOREIGN KEY (`class_id`) REFERENCES `classes` (`id`),
  ADD CONSTRAINT `student_class_history_ibfk_3` FOREIGN KEY (`academic_year_id`) REFERENCES `academic_years` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
