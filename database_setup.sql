-- ============================================
-- LIVORA DATABASE SETUP
-- Run this in phpMyAdmin or MySQL command line
-- ============================================

CREATE DATABASE IF NOT EXISTS livora_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE livora_db;

-- USERS TABLE
CREATE TABLE IF NOT EXISTS users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  email VARCHAR(150) UNIQUE NOT NULL,
  phone VARCHAR(20),
  password VARCHAR(255) NOT NULL,
  role ENUM('seeker','roommate','cook','maid') DEFAULT 'seeker',
  city VARCHAR(100),
  area VARCHAR(100),
  profile_img VARCHAR(255),
  verified TINYINT(1) DEFAULT 0,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ROOMMATES TABLE
CREATE TABLE IF NOT EXISTS roommates (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT,
  city VARCHAR(100),
  area VARCHAR(150),
  budget INT,
  gender ENUM('Male','Female','Any'),
  age INT,
  occupation VARCHAR(100),
  bio TEXT,
  tags VARCHAR(255),
  rating DECIMAL(2,1) DEFAULT 0,
  verified TINYINT(1) DEFAULT 0,
  active TINYINT(1) DEFAULT 1,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- COOKS TABLE
CREATE TABLE IF NOT EXISTS cooks (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT,
  city VARCHAR(100),
  area VARCHAR(150),
  charge INT,
  specialty VARCHAR(100),
  experience INT DEFAULT 0,
  availability ENUM('Full Time','Part Time','Daily','Weekends Only'),
  bio TEXT,
  tags VARCHAR(255),
  rating DECIMAL(2,1) DEFAULT 0,
  verified TINYINT(1) DEFAULT 0,
  active TINYINT(1) DEFAULT 1,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- MAIDS TABLE
CREATE TABLE IF NOT EXISTS maids (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT,
  city VARCHAR(100),
  area VARCHAR(150),
  charge INT,
  experience INT DEFAULT 0,
  availability ENUM('Full Time','Part Time','Daily'),
  services VARCHAR(255),
  bio TEXT,
  tags VARCHAR(255),
  rating DECIMAL(2,1) DEFAULT 0,
  verified TINYINT(1) DEFAULT 0,
  active TINYINT(1) DEFAULT 1,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- MESSAGES TABLE
CREATE TABLE IF NOT EXISTS messages (
  id INT AUTO_INCREMENT PRIMARY KEY,
  from_user INT,
  to_user INT,
  message TEXT NOT NULL,
  listing_type VARCHAR(20),
  listing_id INT,
  is_read TINYINT(1) DEFAULT 0,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (from_user) REFERENCES users(id),
  FOREIGN KEY (to_user) REFERENCES users(id)
);

-- SAVED / FAVORITES TABLE
CREATE TABLE IF NOT EXISTS saved (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT,
  listing_type ENUM('roommate','cook','maid'),
  listing_id INT,
  saved_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id)
);

-- REVIEWS TABLE
CREATE TABLE IF NOT EXISTS reviews (
  id INT AUTO_INCREMENT PRIMARY KEY,
  reviewer_id INT,
  listing_type ENUM('roommate','cook','maid'),
  listing_id INT,
  rating INT CHECK(rating BETWEEN 1 AND 5),
  review TEXT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (reviewer_id) REFERENCES users(id)
);

-- CONTACT MESSAGES TABLE
CREATE TABLE IF NOT EXISTS contact_messages (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100),
  email VARCHAR(150),
  phone VARCHAR(20),
  subject VARCHAR(200),
  message TEXT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Create user accounts through register.php so passwords are securely hashed.

SELECT 'Database setup complete!' AS status;
