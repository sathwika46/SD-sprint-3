CREATE DATABASE IF NOT EXISTS ebiz_demo CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE ebiz_demo;


CREATE TABLE IF NOT EXISTS users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(100) NOT NULL UNIQUE,
  email VARCHAR(255) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  is_admin TINYINT(1) DEFAULT 0,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS products (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(255) NOT NULL,
  description TEXT,
  price DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  stock INT NOT NULL DEFAULT 0,
  image VARCHAR(255),
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS orders (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT,
  total DECIMAL(10,2) NOT NULL,
  billing_name VARCHAR(255),
  billing_email VARCHAR(255),
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS order_items (
  id INT AUTO_INCREMENT PRIMARY KEY,
  order_id INT,
  product_id INT,
  qty INT,
  price DECIMAL(10,2),
  FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
  FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE SET NULL
) ENGINE=InnoDB;

INSERT INTO users (username, email, password_hash, is_admin) VALUES
('admin', 'admin@example.com', CONCAT('$2y$10$', SUBSTRING(REPLACE(UUID(),'-',''),1,44)), 1)
ON DUPLICATE KEY UPDATE username=username;

INSERT INTO products (name, description, price, stock) VALUES
('Blue T-Shirt', 'Comfortable blue t-shirt', 12.99, 10),
('Coffee Mug', 'Ceramic mug 300ml', 7.50, 25),
('Wireless Mouse', 'Ergonomic wireless mouse', 19.99, 15)
ON DUPLICATE KEY UPDATE name = name;
