-- Create the database (if it doesn't exist)
CREATE DATABASE IF NOT EXISTS bookdb;
USE bookdb;

-- Create users table
CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  firstName VARCHAR(50),
  lastName VARCHAR(50),
  email VARCHAR(100) UNIQUE,
  password VARCHAR(255) -- You should hash passwords in production!
);

-- Create books table linked to users
CREATE TABLE books (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT,
  title VARCHAR(150),
  author VARCHAR(100),
  genre VARCHAR(50),
  comments TEXT,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);
