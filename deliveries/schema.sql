-- SQL schema for deliveries service
CREATE DATABASE IF NOT EXISTS deliveries_services;

USE deliveries_services;
-- Create the deliveries table
CREATE TABLE IF NOT EXISTS deliveries (
  id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  order_id INT NOT NULL,
  delivery_status ENUM('assigned','on the way','delivered') NOT NULL DEFAULT 'assigned',
  delivery_time DATETIME DEFAULT NULL,
  current_location VARCHAR(255) DEFAULT NULL
);

INSERT INTO deliveries (order_id, delivery_status, delivery_time, current_location) VALUES
  (1, 'assigned', '2025-05-22 14:00:00', 'Warehouse'),
  (2, 'on the way', '2025-05-23 10:00:00', 'On route'),
  (3, 'delivered', '2025-05-24 12:00:00', 'Delivered');