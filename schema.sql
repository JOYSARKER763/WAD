CREATE DATABASE IF NOT EXISTS campusq CHARACTER SET utf8mb4; USE campusq;
CREATE TABLE services(id INT AUTO_INCREMENT PRIMARY KEY, department VARCHAR(60), name VARCHAR(80), code VARCHAR(4), avg_min INT DEFAULT 5);
CREATE TABLE users(id INT AUTO_INCREMENT PRIMARY KEY, name VARCHAR(80), email VARCHAR(100) UNIQUE, password VARCHAR(255), role ENUM('student','staff','admin') DEFAULT 'student', service_id INT NULL, FOREIGN KEY(service_id) REFERENCES services(id) ON DELETE SET NULL);
CREATE TABLE queue(id INT AUTO_INCREMENT PRIMARY KEY, user_id INT, service_id INT, token_no INT, status ENUM('waiting','serving','done','cancelled','skipped') DEFAULT 'waiting', qdate DATE, created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP, served_at TIMESTAMP NULL, FOREIGN KEY(user_id) REFERENCES users(id) ON DELETE CASCADE, FOREIGN KEY(service_id) REFERENCES services(id) ON DELETE CASCADE);
INSERT INTO services(department,name,code,avg_min) VALUES('Exam Section','Admit Card','EX',5),('Accounts','Fee Issue','AC',10),('Department Office','Certificate Request','DP',8),('ICT Support','Wi-Fi / Portal Problem','IT',6);
-- সব demo user-এর password: password
INSERT INTO users(name,email,password,role,service_id) VALUES
('Admin','admin@campusq.test','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','admin',NULL),
('Rahim (Exam Staff)','staff@campusq.test','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','staff',1),
('Joy','joy@campusq.test','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','student',NULL);
