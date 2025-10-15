
DROP DATABASE IF EXISTS car_rental;
CREATE DATABASE car_rental;
USE car_rental;

-- CREATE TABLES

-- Cars table
CREATE TABLE cars (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    model VARCHAR(100) NOT NULL,
    price_per_day DECIMAL(10,2) NOT NULL,
    available TINYINT(1) DEFAULT 1
);

-- Customers table
CREATE TABLE customers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    phone VARCHAR(20) NOT NULL
);

-- Bookings table
CREATE TABLE bookings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    car_id INT NOT NULL,
    customer_id INT NOT NULL,
    start_date DATE NOT NULL,
    end_date DATE NOT NULL,
    total_price DECIMAL(10,2) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (car_id) REFERENCES cars(id) ON DELETE CASCADE,
    FOREIGN KEY (customer_id) REFERENCES customers(id) ON DELETE CASCADE
);

-- INSERT CARS

INSERT INTO cars (name, model, price_per_day, available) VALUES
('Toyota Corolla', '2021', 50.00, 1),
('Honda Civic', '2022', 60.00, 1),
('Nissan Altima', '2020', 45.00, 1);

-- INSERT UK CUSTOMERS

INSERT INTO customers (name, email, phone) VALUES
('Oliver Smith', 'oliver.smith@example.co.uk', '07123456789'),
('Amelia Johnson', 'amelia.johnson@example.co.uk', '07234567890'),
('Harry Brown', 'harry.brown@example.co.uk', '07345678901');

-- INSERT SAMPLE BOOKINGS

INSERT INTO bookings (car_id, customer_id, start_date, end_date, total_price) VALUES
(1, 1, '2025-10-20', '2025-10-22', 3 * 50.00),  -- Toyota Corolla booked by Oliver
(2, 2, '2025-10-21', '2025-10-23', 3 * 60.00),  -- Honda Civic booked by Amelia
(3, 3, '2025-10-22', '2025-10-24', 3 * 45.00);  -- Nissan Altima booked by Harry

-- VERIFY TABLES

SELECT * FROM cars;
SELECT * FROM customers;
SELECT * FROM bookings;
