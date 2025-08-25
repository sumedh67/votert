
-- Create the database
CREATE DATABASE IF NOT EXISTS online_voting_system;

-- Use the database
USE online_voting_system;

-- Drop tables if they exist (optional for reset)
DROP TABLE IF EXISTS users;
DROP TABLE IF EXISTS candidates;

-- Users Table
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    is_admin TINYINT(1) DEFAULT 0,
    has_voted TINYINT(1) DEFAULT 0
);

-- Candidates Table
CREATE TABLE candidates (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    votes INT DEFAULT 0
);

-- Insert Admin User
-- Password: admin123 (hashed using PHP's password_hash)
INSERT INTO users (full_name, email, password, is_admin)
VALUES (
    'Admin',
    'admin@admin.com',
    '$2y$10$qQiRdt0JYHe89Onwpy4D.OeYx92nHbAgvCz5Le5JK8AJf2vmS8X0W',
    1
);

-- Optional: Insert Sample Candidates
INSERT INTO candidates (name) VALUES
('Candidate A'),
('Candidate B'),
('Candidate C');
