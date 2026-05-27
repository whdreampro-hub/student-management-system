-- ============================================================
-- STUDENT MANAGEMENT SYSTEM - DATABASE SCHEMA
-- ============================================================

CREATE DATABASE IF NOT EXISTS student_management CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE student_management;

-- ============================================================
-- ADMIN TABLE
-- ============================================================
CREATE TABLE IF NOT EXISTS admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    full_name VARCHAR(200) NOT NULL,
    email VARCHAR(200),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Default admin: admin / admin123
INSERT INTO admins (username, password, full_name, email) VALUES
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'System Administrator', 'admin@school.com');
-- Note: password hash above = 'password' (Laravel default). Will be replaced during setup.

-- ============================================================
-- ACADEMIC YEARS TABLE
-- ============================================================
CREATE TABLE IF NOT EXISTS academic_years (
    id INT AUTO_INCREMENT PRIMARY KEY,
    year_name VARCHAR(20) NOT NULL UNIQUE,
    status ENUM('active','inactive') DEFAULT 'inactive',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

INSERT INTO academic_years (year_name, status) VALUES
('2024-2025', 'inactive'),
('2025-2026', 'active'),
('2026-2027', 'inactive');

-- ============================================================
-- CLASSES TABLE
-- ============================================================
CREATE TABLE IF NOT EXISTS classes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    class_name VARCHAR(50) NOT NULL,
    level VARCHAR(50) NOT NULL,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

INSERT INTO classes (class_name, level) VALUES
('P1', 'Primary'),
('P2', 'Primary'),
('P3', 'Primary'),
('P4', 'Primary'),
('P5', 'Primary'),
('P6', 'Primary'),
('S1', 'Secondary'),
('S2', 'Secondary'),
('S3', 'Secondary'),
('S4', 'Secondary'),
('S5', 'Secondary'),
('S6', 'Secondary');

-- ============================================================
-- STUDENTS TABLE
-- ============================================================
CREATE TABLE IF NOT EXISTS students (
    id INT AUTO_INCREMENT PRIMARY KEY,
    registration_number VARCHAR(50) NOT NULL UNIQUE,
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    gender ENUM('Male','Female','Other') NOT NULL,
    date_of_birth DATE NOT NULL,
    photo VARCHAR(255) DEFAULT NULL,
    parent_name VARCHAR(200),
    parent_phone VARCHAR(30),
    guardian_name VARCHAR(200),
    guardian_phone VARCHAR(30),
    address TEXT,
    village VARCHAR(100),
    sector VARCHAR(100),
    district VARCHAR(100),
    email VARCHAR(200),
    nationality VARCHAR(100) DEFAULT 'Rwandan',
    admission_date DATE NOT NULL,
    deleted_at TIMESTAMP NULL DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_registration (registration_number),
    INDEX idx_name (first_name, last_name),
    INDEX idx_deleted (deleted_at)
) ENGINE=InnoDB;

-- ============================================================
-- STUDENT CLASS HISTORY TABLE
-- ============================================================
CREATE TABLE IF NOT EXISTS student_class_history (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    class_id INT NOT NULL,
    academic_year_id INT NOT NULL,
    status ENUM('active','promoted','transferred','completed','repeated') DEFAULT 'active',
    reason ENUM('New Admission','Promotion','Transfer','Repeat') DEFAULT 'New Admission',
    start_date DATE NOT NULL,
    end_date DATE NULL DEFAULT NULL,
    remarks TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE,
    FOREIGN KEY (class_id) REFERENCES classes(id) ON DELETE RESTRICT,
    FOREIGN KEY (academic_year_id) REFERENCES academic_years(id) ON DELETE RESTRICT,
    INDEX idx_student (student_id),
    INDEX idx_status (status),
    INDEX idx_year (academic_year_id)
) ENGINE=InnoDB;

-- ============================================================
-- ACTIVITY LOGS TABLE
-- ============================================================
CREATE TABLE IF NOT EXISTS activity_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    admin_id INT NOT NULL,
    action VARCHAR(100) NOT NULL,
    description TEXT,
    entity_type VARCHAR(50),
    entity_id INT,
    ip_address VARCHAR(45),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (admin_id) REFERENCES admins(id) ON DELETE CASCADE,
    INDEX idx_action (action),
    INDEX idx_created (created_at)
) ENGINE=InnoDB;
