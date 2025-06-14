-- SQL schema for memberships service
CREATE DATABASE IF NOT EXISTS memberships_services;

USE memberships_services;
-- Create the memberships table with deliveries schema structure
CREATE TABLE IF NOT EXISTS memberships (
  id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  points INT DEFAULT 0
);

-- Insert statements can be added here if needed
INSERT INTO memberships (user_id, points) VALUES
  (1, 100),
  (2, 150),
  (3, 200);
