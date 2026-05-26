-- Database Schema for Student Management System

-- Create database if not exists
CREATE DATABASE IF NOT EXISTS student_management;
USE student_management;

-- Academic Years Table
CREATE TABLE academic_years (
    id INT AUTO_INCREMENT PRIMARY KEY,
    year_name VARCHAR(20) NOT NULL,
    status ENUM('active', 'inactive') DEFAULT 'inactive',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Classes Table
CREATE TABLE classes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    class_name VARCHAR(10) NOT NULL,
    level VARCHAR(20),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Students Table
CREATE TABLE students (
    id INT AUTO_INCREMENT PRIMARY KEY,
    registration_number VARCHAR(50) UNIQUE NOT NULL,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    gender ENUM('Male', 'Female', 'Other') NOT NULL,
    date_of_birth DATE,
    photo VARCHAR(255),
    parent_name VARCHAR(100),
    parent_phone VARCHAR(20),
    guardian_name VARCHAR(100),
    guardian_phone VARCHAR(20),
    address TEXT,
    village VARCHAR(100),
    sector VARCHAR(100),
    district VARCHAR(100),
    email VARCHAR(100),
    nationality VARCHAR(50),
    admission_date DATE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Student Class History Table
CREATE TABLE student_class_history (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    class_id INT NOT NULL,
    academic_year_id INT NOT NULL,
    status ENUM('active', 'promoted', 'transferred', 'completed', 'repeated') DEFAULT 'active',
    reason ENUM('New Admission', 'Promotion', 'Transfer', 'Repeat') DEFAULT 'New Admission',
    start_date DATE NOT NULL,
    end_date DATE,
    remarks TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE,
    FOREIGN KEY (class_id) REFERENCES classes(id),
    FOREIGN KEY (academic_year_id) REFERENCES academic_years(id)
);

-- Admin Users Table (for authentication)
CREATE TABLE admin_users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Insert default admin user (username: admin, password: admin123)
-- Note: In production, use hashed passwords. For simplicity, we'll store plaintext here but in real app use password_hash.
INSERT INTO admin_users (username, password) VALUES ('admin', 'admin123')
ON DUPLICATE KEY UPDATE username=username;

-- Insert sample academic years
INSERT INTO academic_years (year_name, status) VALUES 
('2025-2026', 'active'),
('2026-2027', 'inactive')
ON DUPLICATE KEY UPDATE year_name=year_name;

-- Insert sample classes
INSERT INTO classes (class_name, level) VALUES 
('P1', 'Primary'),
('P2', 'Primary'),
('P3', 'Primary'),
('S1', 'Secondary'),
('S2', 'Secondary')
ON DUPLICATE KEY UPDATE class_name=class_name;