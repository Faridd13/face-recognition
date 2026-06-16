-- Database: face_attendance
-- Generation Time: 2026-06-16
-- Description: Schema for Face Recognition Attendance System

CREATE DATABASE IF NOT EXISTS face_attendance DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE face_attendance;

-- Tabel Users
CREATE TABLE users (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    email_verified_at TIMESTAMP NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'guru') NOT NULL DEFAULT 'guru',
    remember_token VARCHAR(100) NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabel Password Reset Tokens
CREATE TABLE password_reset_tokens (
    email VARCHAR(255) PRIMARY KEY,
    token VARCHAR(255) NOT NULL,
    created_at TIMESTAMP NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabel Failed Jobs
CREATE TABLE failed_jobs (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    uuid VARCHAR(255) UNIQUE NOT NULL,
    connection TEXT NOT NULL,
    queue TEXT NOT NULL,
    payload LONGTEXT NOT NULL,
    exception LONGTEXT NOT NULL,
    failed_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabel Personal Access Tokens
CREATE TABLE personal_access_tokens (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    tokenable_type VARCHAR(255) NOT NULL,
    tokenable_id BIGINT UNSIGNED NOT NULL,
    name VARCHAR(255) NOT NULL,
    token VARCHAR(64) UNIQUE NOT NULL,
    abilities TEXT NULL,
    last_used_at TIMESTAMP NULL,
    expires_at TIMESTAMP NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    INDEX idx_personal_access_tokens_tokenable (tokenable_type, tokenable_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabel Students
CREATE TABLE students (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nis VARCHAR(255) UNIQUE NOT NULL,
    name VARCHAR(255) NOT NULL,
    class VARCHAR(255) NOT NULL,
    parent_whatsapp VARCHAR(255) NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabel Conditions
CREATE TABLE conditions (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    light_condition VARCHAR(255) NOT NULL,
    face_angle VARCHAR(255) NOT NULL,
    distance_condition VARCHAR(255) NOT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabel Attendance
CREATE TABLE attendance (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    student_id BIGINT UNSIGNED NOT NULL,
    attendance_date DATE NOT NULL,
    attendance_time TIME NOT NULL,
    status VARCHAR(255) NOT NULL DEFAULT 'hadir',
    confidence DECIMAL(8,2) NULL,
    latency DECIMAL(10,3) NULL,
    light_condition VARCHAR(255) NULL,
    face_angle VARCHAR(255) NULL,
    distance_condition VARCHAR(255) NULL,
    session_id VARCHAR(255) NULL,
    location VARCHAR(255) NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabel Face Data
CREATE TABLE face_data (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    student_id BIGINT UNSIGNED NOT NULL,
    condition_id BIGINT UNSIGNED NOT NULL,
    image_path VARCHAR(255) NOT NULL,
    is_training TINYINT(1) NOT NULL DEFAULT 0,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE,
    FOREIGN KEY (condition_id) REFERENCES conditions(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabel Experiment Logs
CREATE TABLE experiment_logs (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    student_id BIGINT UNSIGNED NOT NULL,
    actual_identity VARCHAR(255) NOT NULL,
    predicted_identity VARCHAR(255) NULL,
    confidence DECIMAL(8,2) NULL,
    latency DECIMAL(10,3) NULL,
    light_condition VARCHAR(255) NULL,
    face_angle VARCHAR(255) NULL,
    distance_condition VARCHAR(255) NULL,
    is_correct TINYINT(1) NULL,
    experiment_type VARCHAR(255) NOT NULL,
    threshold DECIMAL(8,2) NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabel Evaluation Metrics
CREATE TABLE evaluation_metrics (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    total_tests INT NOT NULL DEFAULT 0,
    correct_predictions INT NOT NULL DEFAULT 0,
    accuracy DECIMAL(8,2) NULL,
    precision DECIMAL(8,2) NULL,
    recall DECIMAL(8,2) NULL,
    far DECIMAL(8,2) NULL,
    frr DECIMAL(8,2) NULL,
    avg_latency DECIMAL(10,3) NULL,
    experiment_log_id BIGINT UNSIGNED NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (experiment_log_id) REFERENCES experiment_logs(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO users (name, email, password, role, created_at, updated_at) VALUES
('Administrator', 'admin123@gmail.com', '$2y$10$YourHashedPasswordHere', 'admin', NOW(), NOW()),
('Guru Farid', 'gurufarid@gmail.com', '$2y$10$YourHashedPasswordHere', 'guru', NOW(), NOW());

-- Insert Default Conditions
INSERT INTO conditions (light_condition, face_angle, distance_condition, created_at, updated_at) VALUES
('terang', 'frontal', 'dekat', NOW(), NOW()),
('terang', 'frontal', 'jauh', NOW(), NOW()),
('terang', 'nonfrontal', 'dekat', NOW(), NOW()),
('terang', 'nonfrontal', 'jauh', NOW(), NOW()),
('redup', 'frontal', 'dekat', NOW(), NOW()),
('redup', 'nonfrontal', 'jauh', NOW(), NOW());
