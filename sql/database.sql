-- ============================================
-- GYMZ - Database Schema
-- ============================================
CREATE DATABASE IF NOT EXISTS gymz_db
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE gymz_db;

-- Tabel Admin / User Login
CREATE TABLE IF NOT EXISTS users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  full_name VARCHAR(100) NOT NULL,
  email VARCHAR(150) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Tabel Member Gym
CREATE TABLE IF NOT EXISTS members (
  id INT AUTO_INCREMENT PRIMARY KEY,
  full_name VARCHAR(100) NOT NULL,
  email VARCHAR(150) NOT NULL UNIQUE,
  phone VARCHAR(20) NOT NULL,
  gender ENUM('Male','Female','Other') NOT NULL DEFAULT 'Male',
  birth_date DATE,
  address TEXT,
  membership_type ENUM('Basic','Pro','Elite') NOT NULL DEFAULT 'Basic',
  payment_status ENUM('Pending','Paid') NOT NULL DEFAULT 'Pending',
  payment_method VARCHAR(50) DEFAULT NULL,
  joined_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Akun admin default (password: admin123)
INSERT INTO users (full_name, email, password) VALUES
('Admin Gym', 'admin@gymz.com', '$2y$10$E9Q1Yk0E2rJj1Qp2mF4u5e6bJxq7xYJxXZkY5ZwJ9YqJ0rXy7O8C2')
ON DUPLICATE KEY UPDATE email=email;
