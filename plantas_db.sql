-- Criar o banco de dados
CREATE DATABASE IF NOT EXISTS plantas_db
  DEFAULT CHARACTER SET utf8mb4
  DEFAULT COLLATE utf8mb4_unicode_ci;

-- Usar o banco
USE plantas_db;

-- Criar a tabela plantas
CREATE TABLE plantas (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nome VARCHAR(100) NOT NULL,
  usos TEXT
);
