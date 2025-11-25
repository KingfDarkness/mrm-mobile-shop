-- Database: mrm_mobile_shop

CREATE DATABASE IF NOT EXISTS mrm_mobile_shop;
USE mrm_mobile_shop;

-- Users Table
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    mobile VARCHAR(20),
    address TEXT,
    role ENUM('user', 'admin') DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Products Table
CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    price DECIMAL(10, 2) NOT NULL,
    image VARCHAR(255),
    category VARCHAR(100),
    is_deal_of_day BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Orders Table
CREATE TABLE IF NOT EXISTS orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    total_price DECIMAL(10, 2) NOT NULL,
    status ENUM('pending', 'processing', 'completed', 'cancelled') DEFAULT 'pending',
    shipping_address TEXT NOT NULL,
    payment_method VARCHAR(50) DEFAULT 'cod',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

-- Order Items Table
CREATE TABLE IF NOT EXISTS order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(id),
    FOREIGN KEY (product_id) REFERENCES products(id)
);

-- Reviews Table
CREATE TABLE IF NOT EXISTS reviews (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    user_id INT NOT NULL,
    rating INT CHECK (rating >= 1 AND rating <= 5),
    comment TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (product_id) REFERENCES products(id),
    FOREIGN KEY (user_id) REFERENCES users(id)
);

-- Insert Admin User (Password: admin123)
INSERT INTO users (name, email, password, role) VALUES 
('Admin', 'admin@mrm.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin');

-- Insert Demo Products
INSERT INTO products (name, description, price, image, category, is_deal_of_day) VALUES
('iPhone 15 Pro Max', 'Titanium design, A17 Pro chip.', 180000.00, 'https://placehold.co/600x400?text=iPhone+15', 'Smartphone', TRUE),
('Samsung Galaxy S24 Ultra', 'Galaxy AI is here.', 150000.00, 'https://placehold.co/600x400?text=Samsung+S24', 'Smartphone', FALSE),
('MacBook Air M3', 'Lean. Mean. M3 machine.', 130000.00, 'https://placehold.co/600x400?text=MacBook+Air', 'Laptop', TRUE),
('Sony WH-1000XM5', 'Noise cancelling headphones.', 35000.00, 'https://placehold.co/600x400?text=Sony+Headphones', 'Accessories', FALSE),
('Apple Watch Series 9', 'Smarter. Brighter. Mightier.', 55000.00, 'https://placehold.co/600x400?text=Apple+Watch', 'Wearable', FALSE);
