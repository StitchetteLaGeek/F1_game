-- Création de la base de données
CREATE DATABASE IF NOT EXISTS course_game;
USE course_game;

-- Création de la table des utilisateurs
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    pseudo VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    ecurie VARCHAR(50),
    monaco FLOAT,
    shanghai FLOAT,
    hockenheim FLOAT,
    nuerburgring FLOAT,
    registration_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
