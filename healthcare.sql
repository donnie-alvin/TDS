-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 21, 2024 at 10:00 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `healthcare`
--

-- --------------------------------------------------------

--
-- Table structure for table `appointments`
--

CREATE TABLE `appointments` (
  `appointment_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `doctor_id` int(11) NOT NULL,
  `appointment_date` datetime NOT NULL,
  `status` enum('confirmed','cancelled') DEFAULT 'confirmed',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
 
ALTER TABLE users ADD COLUMN role VARCHAR(20) DEFAULT 'user';
--
-- Dumping data for table `appointments`
--

INSERT INTO `appointments` (`appointment_id`, `user_id`, `doctor_id`, `appointment_date`, `status`, `created_at`) VALUES
(9, 1, 2, '2024-12-30 13:00:00', 'confirmed', '2024-12-20 11:58:24'),
(10, 1, 1, '2024-12-02 11:00:00', 'confirmed', '2024-12-20 12:05:18'),
(11, 1, 4, '2025-01-06 11:00:00', 'confirmed', '2024-12-20 12:07:07'),
(12, 1, 2, '2024-12-03 15:00:00', 'confirmed', '2024-12-20 12:07:59'),
(13, 1, 1, '2024-12-17 13:00:00', 'confirmed', '2024-12-20 12:15:33');

-- --------------------------------------------------------

--
-- Table structure for table `doctors`
--

CREATE TABLE `doctors` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `specialization` varchar(100) DEFAULT NULL,
  `location` varchar(100) DEFAULT NULL,
  `hospital` varchar(100) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `picture` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `doctors`
--

INSERT INTO `doctors` (`id`, `name`, `email`, `password`, `specialization`, `location`, `hospital`, `description`, `picture`, `created_at`) VALUES
(1, 'Dr. Tendai Moyo', 'tendai.moyo@example.com', '$2y$10$xeBlWESzNU6/jhZdDbjWbexcGMlyZTmfn/kTkLzh4T6MJZwH/2w76', 'General Practitioner', 'Harare', 'Harare Hospital', 'Experienced in general health and wellness.', 'images/doctor3.png', '2024-12-20 09:19:24'),
(2, 'Dr. Nyasha Chikwanje', 'nyasha.chikwanje@example.com', '$2y$10$f/tsruzVSEqQc5F/92rMcukMv8akIVz9zcjp.cdnRCzCz3kYxFZOu', 'Pediatrician', 'Bulawayo', 'Children’s Hospital', 'Specializes in child health and development.', 'images/doctor 2.png', '2024-12-20 09:19:24'),
(3, 'Dr. Chipo Ndlovu', 'chipo.ndlovu@example.com', '$2y$10$gFcg74xRr7qAKe7uGVx5gOYDMeF.0v5I9CCNWU6kiT96prNJUjnri', 'Cardiologist', 'Mutare', 'Mutare General Hospital', 'Expert in heart-related conditions.', 'images/doctor 1.png', '2024-12-20 09:19:24'),
(4, 'Dr. Farai Mavhunga', 'farai.mavhunga@example.com', '$2y$10$DiLu1ws5ceoqfH9LubllfeirhmrypPpEPSnSeqZEXtu.G3iW455NC', 'Dermatologist', 'Gweru', 'Gweru Medical Center', 'Focuses on skin health and diseases.', 'images/doctor4.png', '2024-12-20 09:19:24'),
(5, 'Dr. Rudo Mupfumi', 'rudo.mupfumi@example.com', '$2y$10$ugGxoc5ktBRAuQdCV3LZYeWaDyIhoE2COR1YW.hX5INWGcLFydQGe', 'Gynecologist', 'Kwekwe', 'Kwekwe Women’s Clinic', 'Specializes in women’s reproductive health.', 'images/doctor 5.png', '2024-12-20 09:19:24');

-- --------------------------------------------------------

--
-- Table structure for table `doctor_availability`
--

CREATE TABLE `doctor_availability` (
  `id` int(11) NOT NULL,
  `doctor_id` int(11) NOT NULL,
  `available_date` date NOT NULL,
  `available_time` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `doctor_availability`
--

INSERT INTO `doctor_availability` (`id`, `doctor_id`, `available_date`, `available_time`) VALUES
(1, 1, '2024-11-12', '09:00:00'),
(2, 1, '2024-11-12', '10:00:00'),
(3, 1, '2024-11-12', '11:00:00'),
(4, 2, '2024-11-12', '13:00:00'),
(5, 2, '2024-11-12', '14:00:00'),
(6, 2, '2024-11-12', '15:00:00'),
(7, 3, '2024-11-12', '09:30:00'),
(8, 3, '2024-11-12', '10:30:00'),
(9, 3, '2024-11-12', '11:30:00');

-- --------------------------------------------------------

--
-- Table structure for table `temporary_users`
--

CREATE TABLE `temporary_users` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `confirmation_code` varchar(10) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `name` varchar(50) NOT NULL,
  `is_admin` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `password`, `email`, `created_at`, `name`, `is_admin`) VALUES
(1, '', '$2y$10$h6ipkF8Hl24MS9bCu.9Nlehz5y.PKT5FugPjEXYbU89wmFblaJ66m', 'a.phiri@africau.edu', '2024-12-20 09:46:00', 'alvin', 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `appointments`
--
ALTER TABLE `appointments`
  ADD PRIMARY KEY (`appointment_id`),
  ADD KEY `user_id` (`user_id`,`doctor_id`),
  ADD KEY `doctor_id` (`doctor_id`);

--
-- Indexes for table `doctors`
--
ALTER TABLE `doctors`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `doctor_availability`
--
ALTER TABLE `doctor_availability`
  ADD PRIMARY KEY (`id`),
  ADD KEY `doctor_id` (`doctor_id`);

--
-- Indexes for table `temporary_users`
--
ALTER TABLE `temporary_users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

CREATE TABLE feedback (
    id INT PRIMARY KEY AUTO_INCREMENT,
    appointment_id INT,
    rating INT NOT NULL,
    comment TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (appointment_id) REFERENCES appointments(appointment_id)
);
--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `appointments`
--
ALTER TABLE `appointments`
  MODIFY `appointment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `doctors`
--
ALTER TABLE `doctors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `doctor_availability`
--
ALTER TABLE `doctor_availability`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `temporary_users`
--
ALTER TABLE `temporary_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `appointments`
--
ALTER TABLE `appointments`
  ADD CONSTRAINT `appointments_ibfk_1` FOREIGN KEY (`doctor_id`) REFERENCES `doctors` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `appointments_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `doctor_availability`
--
ALTER TABLE `doctor_availability`
  ADD CONSTRAINT `doctor_availability_ibfk_1` FOREIGN KEY (`doctor_id`) REFERENCES `doctors` (`id`);
COMMIT;

UPDATE users SET role = 'user' WHERE role IS NULL;

CREATE TABLE doctor_ratings (
    rating_id INT PRIMARY KEY AUTO_INCREMENT,
    doctor_id INT NOT NULL,
    user_id INT NOT NULL,
    rating INT NOT NULL CHECK (rating >= 1 AND rating <= 5),
    feedback TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (doctor_id) REFERENCES doctors(id),
    FOREIGN KEY (user_id) REFERENCES users(user_id)
);
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
