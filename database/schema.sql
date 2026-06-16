-- Database Schema for Face Recognition Attendance System
CREATE DATABASE IF NOT EXISTS face_attendance;
USE face_attendance;

-- Tabel Users (Admin & Guru)
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'guru') NOT NULL DEFAULT 'guru',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Tabel Siswa
CREATE TABLE students (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nis VARCHAR(20) UNIQUE NOT NULL,
    name VARCHAR(100) NOT NULL,
    class VARCHAR(50),
    parent_email VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Tabel Conditions (Kondisi pengambilan gambar)
CREATE TABLE conditions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    light_condition ENUM('terang', 'normal', 'gelap') NOT NULL,
    face_angle ENUM('depan', 'kiri', 'kanan', 'atas', 'bawah') NOT NULL,
    distance_condition ENUM('dekat', 'sedang', 'jauh') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabel Face Data (Data wajah untuk training)
CREATE TABLE face_data (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    condition_id INT NOT NULL,
    image_path VARCHAR(255) NOT NULL,
    is_training TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE,
    FOREIGN KEY (condition_id) REFERENCES conditions(id) ON DELETE CASCADE
);

-- Tabel Attendance (Presensi)
CREATE TABLE attendance (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    attendance_date DATE NOT NULL,
    attendance_time TIME NOT NULL,
    status ENUM('hadir', 'sakit', 'izin', 'alpha') DEFAULT 'hadir',
    confidence DECIMAL(5,2),
    latency DECIMAL(10,3),
    light_condition VARCHAR(20),
    face_angle VARCHAR(20),
    distance_condition VARCHAR(20),
    session_id VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE,
    UNIQUE KEY unique_attendance (student_id, attendance_date, session_id)
);

-- Tabel Experiment Logs (Log pengujian)
CREATE TABLE experiment_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    actual_identity VARCHAR(100) NOT NULL,
    predicted_identity VARCHAR(100),
    confidence DECIMAL(5,2),
    latency DECIMAL(10,3),
    light_condition VARCHAR(20),
    face_angle VARCHAR(20),
    distance_condition VARCHAR(20),
    is_correct TINYINT(1),
    experiment_type ENUM('training', 'testing') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE
);

-- Tabel Evaluation Metrics (Hasil evaluasi)
CREATE TABLE evaluation_metrics (
    id INT AUTO_INCREMENT PRIMARY KEY,
    total_tests INT DEFAULT 0,
    correct_predictions INT DEFAULT 0,
    accuracy DECIMAL(5,2),
    precision DECIMAL(5,2),
    recall DECIMAL(5,2),
    far DECIMAL(5,2),
    frr DECIMAL(5,2),
    avg_latency DECIMAL(10,3),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert default conditions
INSERT INTO conditions (light_condition, face_angle, distance_condition) VALUES
-- Kondisi 1: Terang, Depan, Sedang
('terang', 'depan', 'sedang'),
-- Kondisi 2: Normal, Depan, Sedang
('normal', 'depan', 'sedang'),
-- Kondisi 3: Gelap, Depan, Sedang
('gelap', 'depan', 'sedang'),
-- Kondisi 4: Normal, Kiri, Sedang
('normal', 'kiri', 'sedang'),
-- Kondisi 5: Normal, Kanan, Sedang
('normal', 'kanan', 'sedang'),
-- Kondisi 6: Normal, Depan, Dekat
('normal', 'depan', 'dekat');

-- Insert default admin user (password: admin123)
INSERT INTO users (name, email, password, role) VALUES
('Administrator', 'admin@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin'),
('Guru Budi', 'guru@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'guru');
