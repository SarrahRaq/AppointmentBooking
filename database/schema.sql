-- Appointment Booking System - database schema
-- Create a database first (local: appointmentdb, InfinityFree: your if0_xxx database),
-- then import this file in phpMyAdmin.

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    fullname VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin','user') NOT NULL DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE appointments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    service VARCHAR(100) NOT NULL,
    appt_date DATE NOT NULL,
    appt_time TIME NOT NULL,
    notes VARCHAR(255),
    status ENUM('Pending','Confirmed','Cancelled') NOT NULL DEFAULT 'Pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Default admin account  (email: admin@pinkbook.com | password: Admin@123)
INSERT INTO users (fullname, email, password, role) VALUES
('Administrator', 'admin@pinkbook.com',
 '$2y$10$9uPaouB6FAIt9zvJfgu/N.agWXeiG73aEvwQEsGpQ.oak/HSgg/xG', 'admin');
