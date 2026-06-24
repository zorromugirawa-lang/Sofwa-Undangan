-- Database for Sofwa Undangan
CREATE DATABASE IF NOT EXISTS db_sofwa_undangan;
USE db_sofwa_undangan;

-- Table for wedding templates
CREATE TABLE IF NOT EXISTS templates (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    category VARCHAR(50),
    thumbnail VARCHAR(255),
    price DECIMAL(10, 2)
);

-- Table for customer orders
CREATE TABLE IF NOT EXISTS orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    customer_name VARCHAR(100) NOT NULL,
    whatsapp_number VARCHAR(20) NOT NULL,
    template_id INT,
    order_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status ENUM('pending', 'processing', 'completed', 'cancelled') DEFAULT 'pending',
    FOREIGN KEY (template_id) REFERENCES templates(id)
);

-- Table for guestbook (Ucapan & Doa)
CREATE TABLE IF NOT EXISTS guestbook (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    message TEXT NOT NULL,
    is_present BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert sample templates
INSERT INTO templates (name, category, thumbnail, price) VALUES
('Template Elegant', 'Premium', 'img/elegant.png', 150000),
('Template Minimalis', 'Simple', 'img/minimalist.png', 100000),
('Template Adat Jawa', 'Pernikahan', 'img/cover_adat_jawa.png', 125000),
('Template Modern', 'Modern', 'img/modern.png', 130000);
