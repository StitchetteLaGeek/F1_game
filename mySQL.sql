CREATE DATABASE IF NOT EXISTS e2202522;
USE e2202522;

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    pseudo VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    ecurie VARCHAR(50),
    CONSTRAINT chk_email CHECK (email LIKE '%@%.%')
);

CREATE INDEX idx_email ON users(email);
