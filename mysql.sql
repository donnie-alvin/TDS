-- Database schema for Doctor Appointment Booking System

CREATE DATABASE IF NOT EXISTS healthcare;

USE healthcare;

-- Table for storing user information
CREATE TABLE IF NOT EXISTS users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table for storing doctor information
CREATE TABLE IF NOT EXISTS doctors (
    doctor_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    specialization VARCHAR(100) NOT NULL,
    availability VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table for storing appointment details
CREATE TABLE IF NOT EXISTS appointments (
    appointment_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    doctor_id INT NOT NULL,
    appointment_date DATETIME NOT NULL,
    status ENUM('confirmed', 'cancelled') DEFAULT 'confirmed',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id),
    FOREIGN KEY (doctor_id) REFERENCES doctors(doctor_id)
);

-- Table for temporarily storing new user information
CREATE TABLE IF NOT EXISTS temporary_users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255),
    email VARCHAR(255) UNIQUE,
    password VARCHAR(255),
    confirmation_code VARCHAR(10),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);


USE healthcare;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    phone VARCHAR(15) NULL
);


CREATE TABLE doctors (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    specialization VARCHAR(100),
    location VARCHAR(100), 
    hospital VARCHAR(100), 
    description TEXT, 
    picture VARCHAR(255), 
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE appointments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    doctor_id INT NOT NULL,
    appointment_date DATETIME NOT NULL,
    status ENUM('confirmed', 'cancelled', 'in_consultation', 'completed') DEFAULT 'confirmed',
    video_session_id VARCHAR(255) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (doctor_id) REFERENCES doctors(id)
);


CREATE TABLE doctor_availability (
    id INT AUTO_INCREMENT PRIMARY KEY,
    doctor_id INT NOT NULL,
    available_date DATE NOT NULL,
    available_time TIME NOT NULL,
    FOREIGN KEY (doctor_id) REFERENCES doctors(id)
);

CREATE TABLE doctor_ratings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    doctor_id INT NOT NULL,
    user_id INT NOT NULL,
    rating INT NOT NULL,
    feedback TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (doctor_id) REFERENCES doctors(id),
    FOREIGN KEY (user_id) REFERENCES users(id)
);


INSERT INTO doctors (name, email, password, specialization, location, hospital, description, picture) VALUES
('Dr. Tendai Moyo', 'tendai.moyo@example.com', '$2y$10$KIX1Z1Z1Z1Z1Z1Z1Z1Z1Z1Z1Z1Z1Z1Z1Z1Z1Z1Z1Z1Z1Z1Z1Z1Z1', 'General Practitioner', 'Harare', 'Harare Hospital', 'Experienced in general health and wellness.', 'images/doctor3.png'),
('Dr. Nyasha Chikwanje', 'nyasha.chikwanje@example.com', '$2y$10$LJ1Z1Z1Z1Z1Z1Z1Z1Z1Z1Z1Z1Z1Z1Z1Z1Z1Z1Z1Z1Z1Z1Z1Z1Z1', 'Pediatrician', 'Bulawayo', 'Children’s Hospital', 'Specializes in child health and development.', 'images/doctor 2.png'),
('Dr. Chipo Ndlovu', 'chipo.ndlovu@example.com', '$2y$10$MNO1Z1Z1Z1Z1Z1Z1Z1Z1Z1Z1Z1Z1Z1Z1Z1Z1Z1Z1Z1Z1Z1Z1Z1Z1', 'Cardiologist', 'Mutare', 'Mutare General Hospital', 'Expert in heart-related conditions.', 'images/doctor 1.png'),
('Dr. Farai Mavhunga', 'farai.mavhunga@example.com', '$2y$10$PQ1Z1Z1Z1Z1Z1Z1Z1Z1Z1Z1Z1Z1Z1Z1Z1Z1Z1Z1Z1Z1Z1Z1Z1Z1', 'Dermatologist', 'Gweru', 'Gweru Medical Center', 'Focuses on skin health and diseases.', 'images/doctor4.png'),
('Dr. Rudo Mupfumi', 'rudo.mupfumi@example.com', '$2y$10$RST1Z1Z1Z1Z1Z1Z1Z1Z1Z1Z1Z1Z1Z1Z1Z1Z1Z1Z1Z1Z1Z1Z1Z1Z1', 'Gynecologist', 'Kwekwe', 'Kwekwe Women’s Clinic', 'Specializes in women’s reproductive health.', 'images/doctor 5.png');

INSERT INTO doctor_availability (doctor_id, available_date, available_time) VALUES
(1, '2024-11-12', '09:00:00'),
(1, '2024-11-12', '10:00:00'),
(1, '2024-11-12', '11:00:00'),
(2, '2024-11-12', '13:00:00'),
(2, '2024-11-12', '14:00:00'),
(2, '2024-11-12', '15:00:00'),
(3, '2024-11-12', '09:30:00'),
(3, '2024-11-12', '10:30:00'),
(3, '2024-11-12', '11:30:00');

INSERT INTO doctor_availability (doctor_id, available_date, available_time) VALUES
(1, '2024-11-12', '08:00:00'),
(1, '2024-11-12', '09:00:00'),
(1, '2024-11-12', '10:00:00'),
(1, '2024-11-12', '11:00:00'),
(1, '2024-11-12', '12:00:00'),
(1, '2024-11-12', '13:00:00'),
(1, '2024-11-12', '14:00:00'),
(2, '2024-11-12', '09:00:00'),
(2, '2024-11-12', '10:00:00'),
(2, '2024-11-12', '11:00:00'),
(2, '2024-11-12', '12:00:00'),
(2, '2024-11-12', '13:00:00'),
(2, '2024-11-12', '14:00:00'),
(2, '2024-11-12', '15:00:00'),
(3, '2024-11-12', '09:30:00'),
(3, '2024-11-12', '10:30:00'),
(3, '2024-11-12', '11:30:00'),
(3, '2024-11-12', '12:30:00'),                                                                                                                                                                                                                          
(3, '2024-11-12', '13:30:00');

UPDATE doctors 
SET password = '$2y$10$xeBlWESzNU6/jhZdDbjWbexcGMlyZTmfn/kTkLzh4T6MJZwH/2w76' 
WHERE email = 'tendai.moyo@example.com';

UPDATE doctors 
SET password = '$2y$10$f/tsruzVSEqQc5F/92rMcukMv8akIVz9zcjp.cdnRCzCz3kYxFZOu' 
WHERE email = 'nyasha.chikwanje@example.com';

UPDATE doctors 
SET password = '$2y$10$gFcg74xRr7qAKe7uGVx5gOYDMeF.0v5I9CCNWU6kiT96prNJUjnri' 
WHERE email = 'chipo.ndlovu@example.com';

UPDATE doctors 
SET password = '$2y$10$DiLu1ws5ceoqfH9LubllfeirhmrypPpEPSnSeqZEXtu.G3iW455NC' 
WHERE email = 'farai.mavhunga@example.com';

UPDATE doctors 
SET password = '$2y$10$ugGxoc5ktBRAuQdCV3LZYeWaDyIhoE2COR1YW.hX5INWGcLFydQGe' 
WHERE email = 'rudo.mupfumi@example.com';