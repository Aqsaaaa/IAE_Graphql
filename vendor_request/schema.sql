-- SQL schema for vendor_requests service
CREATE DATABASE IF NOT EXISTS vendor_requests_service;

USE vendor_requests_service;
-- Create the vendor_requests table with deliveries schema structure
CREATE TABLE IF NOT EXISTS vendor_requests (
  id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  vendor_id INT NOT NULL,
  ingredient_id INT NOT NULL,
  quantity INT NOT NULL,
  status ENUM('requested','accepted','on-delivery','delivered') NOT NULL DEFAULT 'requested',
  requested_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  estimated_arrival DATETIME DEFAULT NULL
);

-- Insert statements can be added here if needed
INSERT INTO vendor_requests (vendor_id, ingredient_id, quantity, status, estimated_arrival) VALUES
  (1, 1, 100, 'requested', '2025-05-22 14:00:00'),
  (2, 2, 200, 'accepted', '2025-05-23 10:00:00'),
  (3, 3, 300, 'on-delivery', '2025-05-24 12:00:00'),
  (4, 4, 400, 'delivered', '2025-05-25 14:00:00');
  