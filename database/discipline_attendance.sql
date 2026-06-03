-- ============================================================
-- Discipline & Attendance System Migration
-- Run this on your student_management database
-- ============================================================

USE `student_management`;

-- --------------------------------------------------------
-- Table: student_behavior_marks
-- Tracks each student's current behavior mark balance
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `student_behavior_marks` (
  `id`          int(11) NOT NULL AUTO_INCREMENT,
  `student_id`  int(11) NOT NULL,
  `marks`       int(11) NOT NULL DEFAULT 40 COMMENT 'Current balance, starts at 40',
  `created_at`  timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at`  timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_student` (`student_id`),
  CONSTRAINT `sbm_student_fk` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table: discipline_records
-- Each deduction event logged by principal
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `discipline_records` (
  `id`              int(11) NOT NULL AUTO_INCREMENT,
  `student_id`      int(11) NOT NULL,
  `marks_removed`   int(11) NOT NULL,
  `marks_before`    int(11) NOT NULL,
  `marks_after`     int(11) NOT NULL,
  `reason`          text NOT NULL,
  `removed_by`      varchar(200) NOT NULL COMMENT 'Name of person who removed marks',
  `admin_id`        int(11) NOT NULL,
  `incident_date`   date NOT NULL,
  `created_at`      timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_student`  (`student_id`),
  KEY `idx_date`     (`incident_date`),
  CONSTRAINT `dr_student_fk` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE,
  CONSTRAINT `dr_admin_fk`   FOREIGN KEY (`admin_id`)   REFERENCES `admins` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table: attendance
-- Daily per-student attendance records
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `attendance` (
  `id`              int(11) NOT NULL AUTO_INCREMENT,
  `student_id`      int(11) NOT NULL,
  `class_id`        int(11) NOT NULL,
  `academic_year_id` int(11) NOT NULL,
  `attendance_date` date NOT NULL,
  `status`          enum('present','absent','late','excused') NOT NULL DEFAULT 'present',
  `remarks`         varchar(255) DEFAULT NULL,
  `recorded_by`     int(11) NOT NULL COMMENT 'admin_id',
  `created_at`      timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at`      timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_student_date` (`student_id`, `attendance_date`),
  KEY `idx_class_date` (`class_id`, `attendance_date`),
  KEY `idx_student`    (`student_id`),
  CONSTRAINT `att_student_fk` FOREIGN KEY (`student_id`)       REFERENCES `students` (`id`) ON DELETE CASCADE,
  CONSTRAINT `att_class_fk`   FOREIGN KEY (`class_id`)         REFERENCES `classes` (`id`),
  CONSTRAINT `att_year_fk`    FOREIGN KEY (`academic_year_id`) REFERENCES `academic_years` (`id`),
  CONSTRAINT `att_admin_fk`   FOREIGN KEY (`recorded_by`)      REFERENCES `admins` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Initialize behavior marks for existing students who don't have a record yet
INSERT IGNORE INTO `student_behavior_marks` (`student_id`, `marks`)
SELECT `id`, 40 FROM `students` WHERE `deleted_at` IS NULL;
