-- ============================================================
-- Sahara Foundation - Membership Registration Database
-- Import this file in phpMyAdmin (Import tab) to create the
-- database and table needed for the membership form.
-- ============================================================

CREATE DATABASE IF NOT EXISTS if0_42258729_sahara_foundation
CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE if0_42258729_sahara_foundation;

CREATE TABLE IF NOT EXISTS members (
  id INT AUTO_INCREMENT PRIMARY KEY,
  registration_id VARCHAR(20) NOT NULL UNIQUE,
  full_name VARCHAR(150) NOT NULL,
  email VARCHAR(150) NOT NULL,
  phone VARCHAR(20) NOT NULL,
  state VARCHAR(100) NOT NULL DEFAULT 'Delhi',
  district VARCHAR(100) NOT NULL,
  village VARCHAR(150) NOT NULL,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;
