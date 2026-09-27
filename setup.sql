CREATE DATABASE IF NOT EXISTS market_link;
USE market_link;

-- 1. Create a new user with a strong password restricted to localhost
CREATE USER IF NOT EXISTS 'market_link_app'@'localhost' IDENTIFIED BY 'marketlink@123';
ALTER USER 'market_link_app'@'localhost' IDENTIFIED BY 'marketlink@123';

-- 2. Grant privileges ONLY to your app's database
GRANT SELECT, INSERT, UPDATE, DELETE, CREATE, DROP, ALTER 
ON `market_link`.* 
TO 'market_link_app'@'localhost';

-- 3. Apply the changes
FLUSH PRIVILEGES;

-- Create users table
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    role ENUM('customer', 'farmer') DEFAULT 'customer',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Create farmers table (for additional info)
CREATE TABLE IF NOT EXISTS farmers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    farm_name VARCHAR(100),
    farm_location VARCHAR(255),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);
