-- SportConnect Database Schema
-- Run this in your MySQL database (e.g., phpMyAdmin)

CREATE DATABASE IF NOT EXISTS sportconnect;
USE sportconnect;

-- Users table to store all user types
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_type ENUM('player', 'coach', 'turf') NOT NULL,
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    phone VARCHAR(20) NOT NULL,
    password VARCHAR(255) NOT NULL,
    
    -- Player specific fields
    location VARCHAR(255),
    sport VARCHAR(100),
    skill_level VARCHAR(50),
    
    -- Coach specific fields
    certification VARCHAR(255),
    specialization VARCHAR(255),
    experience TEXT,
    
    -- Turf specific fields
    turf_name VARCHAR(255),
    turf_address TEXT,
    pin_code VARCHAR(10),
    contact_person VARCHAR(100),
    business_phone VARCHAR(20),
    available_sports TEXT,
    
    -- Common fields
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    INDEX idx_user_type (user_type),
    INDEX idx_email (email)
);

-- Sample data for testing
-- Note: These are test passwords that should be changed in production
INSERT INTO users (user_type, first_name, last_name, email, phone, password, location, sport, skill_level) VALUES
('player', 'John', 'Doe', 'john@example.com', '+1234567890', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'New York', 'football', 'intermediate');

-- Test user with simple password for development (password: "password")
INSERT INTO users (user_type, first_name, last_name, email, phone, password, location, sport, skill_level) VALUES
('player', 'Test', 'User', 'test@example.com', '1234567890', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Test City', 'cricket', 'beginner');

INSERT INTO users (user_type, first_name, last_name, email, phone, password, location, certification, specialization, experience) VALUES
('coach', 'Jane', 'Smith', 'jane@example.com', '+1234567891', '$2y$10$example_hash', 'Los Angeles', 'UEFA A License', 'Football', '5 years');

INSERT INTO users (user_type, first_name, last_name, email, phone, password, turf_name, turf_address, location, pin_code, contact_person, business_phone, available_sports) VALUES
('turf', 'Mike', 'Johnson', 'mike@example.com', '+1234567892', '$2y$10$example_hash', 'Central Sports Complex', '123 Main St, City', 'City Center', '12345', 'Mike Johnson', '+1234567893', 'football, cricket, tennis');
