CREATE DATABASE IF NOT EXISTS course_game;
USE course_game;

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    pseudo VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    ecurie VARCHAR(50),
    monaco FLOAT DEFAULT NULL,
    shanghai FLOAT DEFAULT NULL,
    hockenheim FLOAT DEFAULT NULL,
    nuerburgring FLOAT DEFAULT NULL,
    registration_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT chk_email CHECK (email LIKE '%@%.%')
);

CREATE INDEX idx_email ON users(email);
