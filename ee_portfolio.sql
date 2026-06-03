-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 03, 2026 at 07:36 AM
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
-- Database: `ee_portfolio`
--

-- --------------------------------------------------------

--
-- Table structure for table `certifications`
--

CREATE TABLE `certifications` (
  `id` int(11) NOT NULL,
  `name` varchar(150) NOT NULL,
  `issuer` varchar(100) DEFAULT NULL,
  `year` year(4) DEFAULT NULL,
  `badge_url` varchar(200) DEFAULT NULL,
  `verify_url` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `certifications`
--

INSERT INTO `certifications` (`id`, `name`, `issuer`, `year`, `badge_url`, `verify_url`) VALUES
(1, 'Registered Electrical Engineer (REE)', 'Professional Regulation Commission', '2019', NULL, '#'),
(2, 'Registered Master Electrician (RME)', 'Professional Regulation Commission', '2019', NULL, '#'),
(3, 'IEC 61850 Engineering Fundamentals', 'OMICRON Academy', '2022', NULL, '#'),
(4, 'CCNA – Cisco Certified Network Associate', 'Cisco', '2023', NULL, '#'),
(5, 'Registered Electrical Engineer (REE)', 'Professional Regulation Commission', '2019', NULL, '#'),
(6, 'Registered Master Electrician (RME)', 'Professional Regulation Commission', '2019', NULL, '#'),
(7, 'IEC 61850 Engineering Fundamentals', 'OMICRON Academy', '2022', NULL, '#'),
(8, 'CCNA – Cisco Certified Network Associate', 'Cisco', '2023', NULL, '#');

-- --------------------------------------------------------

--
-- Table structure for table `contact_messages`
--

CREATE TABLE `contact_messages` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `subject` varchar(200) DEFAULT NULL,
  `message` text NOT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  `ip_address` varchar(45) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `education`
--

CREATE TABLE `education` (
  `id` int(11) NOT NULL,
  `institution` varchar(150) NOT NULL,
  `degree` varchar(150) NOT NULL,
  `field` varchar(100) DEFAULT NULL,
  `period` varchar(60) DEFAULT NULL,
  `gpa` varchar(20) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `sort_order` smallint(6) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `education`
--

INSERT INTO `education` (`id`, `institution`, `degree`, `field`, `period`, `gpa`, `description`, `sort_order`) VALUES
(1, 'Xavier University – Ateneo de Cagayan', 'Bachelor of Science', 'Electrical Engineering', '2015 – 2019', '1.45 / 1.0 scale', 'Graduated with honors (Cum Laude). Thesis: \"Design of an Adaptive Volt/VAR Optimization Algorithm for Radial Distribution Networks.\" President, Institute of Electrical Engineers – Student Chapter.', 1),
(2, 'Mindanao State University', 'Short Course', 'Industrial Automation & PLC Programming', '2021', NULL, 'Intensive 80-hour course covering Siemens S7-1200, ladder logic, and HMI programming with WinCC.', 2),
(3, 'Xavier University – Ateneo de Cagayan', 'Bachelor of Science', 'Electrical Engineering', '2015 – 2019', '1.45 / 1.0 scale', 'Graduated with honors (Cum Laude). Thesis: \"Design of an Adaptive Volt/VAR Optimization Algorithm for Radial Distribution Networks.\" President, Institute of Electrical Engineers – Student Chapter.', 1),
(4, 'Mindanao State University', 'Short Course', 'Industrial Automation & PLC Programming', '2021', NULL, 'Intensive 80-hour course covering Siemens S7-1200, ladder logic, and HMI programming with WinCC.', 2);

-- --------------------------------------------------------

--
-- Table structure for table `experience`
--

CREATE TABLE `experience` (
  `id` int(11) NOT NULL,
  `company` varchar(150) NOT NULL,
  `role` varchar(150) NOT NULL,
  `period` varchar(60) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `is_current` tinyint(1) DEFAULT 0,
  `sort_order` smallint(6) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `experience`
--

INSERT INTO `experience` (`id`, `company`, `role`, `period`, `description`, `is_current`, `sort_order`) VALUES
(1, 'Northern Mindanao Power Corp.', 'Power Systems Engineer', '2022 – Present', 'Lead engineer for substation automation upgrades across 6 substations (69kV–138kV). Responsible for IEC 61850 relay configuration, SCADA integration, and protection coordination studies. Reduced unplanned outage time by 28% YoY.', 1, 1),
(2, 'Solaris Energy Solutions', 'Embedded Systems Engineer', '2020 – 2022', 'Designed firmware and hardware for solar inverter products (1kW–10kW). Delivered MPPT algorithm improvements that boosted energy harvest by 6%. Managed PCB layout reviews and EMC pre-compliance testing.', 0, 2),
(3, 'Integrated Microelectronics Inc.', 'Junior Hardware Engineer (Intern)', '2019 – 2020', 'Supported PCB layout for industrial motor drive boards. Conducted automated functional testing using Python + VISA. Contributed to design reviews and DFM analysis.', 0, 3),
(4, 'Northern Mindanao Power Corp.', 'Power Systems Engineer', '2022 – Present', 'Lead engineer for substation automation upgrades across 6 substations (69kV–138kV). Responsible for IEC 61850 relay configuration, SCADA integration, and protection coordination studies. Reduced unplanned outage time by 28% YoY.', 1, 1),
(5, 'Solaris Energy Solutions', 'Embedded Systems Engineer', '2020 – 2022', 'Designed firmware and hardware for solar inverter products (1kW–10kW). Delivered MPPT algorithm improvements that boosted energy harvest by 6%. Managed PCB layout reviews and EMC pre-compliance testing.', 0, 2),
(6, 'Integrated Microelectronics Inc.', 'Junior Hardware Engineer (Intern)', '2019 – 2020', 'Supported PCB layout for industrial motor drive boards. Conducted automated functional testing using Python + VISA. Contributed to design reviews and DFM analysis.', 0, 3);

-- --------------------------------------------------------

--
-- Table structure for table `profile`
--

CREATE TABLE `profile` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `title` varchar(150) NOT NULL,
  `tagline` varchar(255) DEFAULT NULL,
  `about` text DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `phone` varchar(30) DEFAULT NULL,
  `location` varchar(100) DEFAULT NULL,
  `github` varchar(200) DEFAULT NULL,
  `linkedin` varchar(200) DEFAULT NULL,
  `resume_url` varchar(200) DEFAULT NULL,
  `avatar_url` varchar(200) DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `profile`
--

INSERT INTO `profile` (`id`, `name`, `title`, `tagline`, `about`, `email`, `phone`, `location`, `github`, `linkedin`, `resume_url`, `avatar_url`, `updated_at`) VALUES
(1, 'Alex Reyes', 'Electrical Engineer', 'Designing circuits that power tomorrow.', 'Passionate Electrical Engineer with 5+ years of experience in power systems, embedded firmware, and PCB design. I bridge the gap between hardware and software — from high-voltage substation automation to low-power IoT sensor nodes. I love tackling complex problems and delivering reliable, efficient solutions.', 'alex.reyes@email.com', '+63 912 345 6789', 'Cagayan de Oro, Philippines', 'https://github.com/alexreyes', 'https://linkedin.com/in/alexreyes', '#', NULL, '2026-06-03 02:06:30'),
(2, 'Alex Reyes', 'Electrical Engineer', 'Designing circuits that power tomorrow.', 'Passionate Electrical Engineer with 5+ years of experience in power systems, embedded firmware, and PCB design. I bridge the gap between hardware and software — from high-voltage substation automation to low-power IoT sensor nodes. I love tackling complex problems and delivering reliable, efficient solutions.', 'alex.reyes@email.com', '+63 912 345 6789', 'Cagayan de Oro, Philippines', 'https://github.com/alexreyes', 'https://linkedin.com/in/alexreyes', '#', NULL, '2026-06-03 02:15:45');

-- --------------------------------------------------------

--
-- Table structure for table `projects`
--

CREATE TABLE `projects` (
  `id` int(11) NOT NULL,
  `title` varchar(150) NOT NULL,
  `category` varchar(80) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `image_url` varchar(200) DEFAULT NULL,
  `github_url` varchar(200) DEFAULT NULL,
  `demo_url` varchar(200) DEFAULT NULL,
  `tags` varchar(255) DEFAULT NULL COMMENT 'Comma-separated tag list',
  `featured` tinyint(1) DEFAULT 0,
  `sort_order` smallint(6) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `projects`
--

INSERT INTO `projects` (`id`, `title`, `category`, `description`, `image_url`, `github_url`, `demo_url`, `tags`, `featured`, `sort_order`, `created_at`) VALUES
(1, 'Smart Grid Fault Detection System', 'Power Systems', 'Developed a real-time fault detection system for a 33kV distribution network using IEC 61850 protocol. Integrated ML-based anomaly detection (Python / scikit-learn) with SCADA data streams, reducing fault response time by 40%.', 'images/proj_grid.jpg', 'https://github.com/alexreyes/smart-grid', NULL, 'IEC 61850,Python,SCADA,ML,Power Systems', 1, 1, '2026-06-03 02:06:30'),
(2, 'Solar MPPT Charge Controller', 'Power Electronics', 'Designed and fabricated a 400W MPPT solar charge controller on a 4-layer PCB using STM32 + custom gate-driver ICs. Achieved 97.2% efficiency. Firmware written in C with FreeRTOS task scheduling.', 'images/proj_solar.jpg', 'https://github.com/alexreyes/mppt-controller', NULL, 'STM32,PCB,FreeRTOS,Solar,MPPT', 1, 2, '2026-06-03 02:06:30'),
(3, 'Industrial IoT Vibration Monitor', 'Embedded Systems', 'End-to-end IoT solution for predictive maintenance: MEMS accelerometer node (ESP32) → MQTT broker → InfluxDB → Grafana dashboard. Deployed on 12 rotating machines in a cement plant.', 'images/proj_iot.jpg', 'https://github.com/alexreyes/vib-monitor', NULL, 'ESP32,MQTT,IoT,Python,Grafana', 1, 3, '2026-06-03 02:06:30'),
(4, '3-Phase Inverter Design', 'Power Electronics', 'Designed and simulated a 5kW 3-phase VSI in MATLAB/Simulink with SPWM control. Hardware prototype built on custom PCB with IGBT modules. Tested THD < 3% at full load.', 'images/proj_inverter.jpg', 'https://github.com/alexreyes/3phase-inverter', NULL, 'MATLAB,Simulink,PCB,IGBT,Power Electronics', 0, 4, '2026-06-03 02:06:30'),
(5, 'Substation HMI Dashboard', 'Power Systems', 'Built a responsive SCADA HMI web app (PHP + MySQL + Chart.js) for a 69kV substation. Displays real-time analog values, breaker status, and alarm logs. Role-based access control.', 'images/proj_hmi.jpg', '#', NULL, 'PHP,MySQL,Chart.js,SCADA,HMI', 0, 5, '2026-06-03 02:06:30'),
(6, 'Low-Power Air Quality Sensor', 'Embedded Systems', 'Ultra low-power (< 15µA sleep) air quality node using nRF52840 + SHT40 + SPS30 particulate sensor. BLE advertising with custom GATT profile. 2-year coin cell battery life achieved.', 'images/proj_aq.jpg', 'https://github.com/alexreyes/aq-sensor', NULL, 'nRF52840,BLE,Low Power,C,IoT', 0, 6, '2026-06-03 02:06:30'),
(7, 'Smart Grid Fault Detection System', 'Power Systems', 'Developed a real-time fault detection system for a 33kV distribution network using IEC 61850 protocol. Integrated ML-based anomaly detection (Python / scikit-learn) with SCADA data streams, reducing fault response time by 40%.', 'images/proj_grid.jpg', 'https://github.com/alexreyes/smart-grid', NULL, 'IEC 61850,Python,SCADA,ML,Power Systems', 1, 1, '2026-06-03 02:15:45'),
(8, 'Solar MPPT Charge Controller', 'Power Electronics', 'Designed and fabricated a 400W MPPT solar charge controller on a 4-layer PCB using STM32 + custom gate-driver ICs. Achieved 97.2% efficiency. Firmware written in C with FreeRTOS task scheduling.', 'images/proj_solar.jpg', 'https://github.com/alexreyes/mppt-controller', NULL, 'STM32,PCB,FreeRTOS,Solar,MPPT', 1, 2, '2026-06-03 02:15:45'),
(9, 'Industrial IoT Vibration Monitor', 'Embedded Systems', 'End-to-end IoT solution for predictive maintenance: MEMS accelerometer node (ESP32) → MQTT broker → InfluxDB → Grafana dashboard. Deployed on 12 rotating machines in a cement plant.', 'images/proj_iot.jpg', 'https://github.com/alexreyes/vib-monitor', NULL, 'ESP32,MQTT,IoT,Python,Grafana', 1, 3, '2026-06-03 02:15:45'),
(10, '3-Phase Inverter Design', 'Power Electronics', 'Designed and simulated a 5kW 3-phase VSI in MATLAB/Simulink with SPWM control. Hardware prototype built on custom PCB with IGBT modules. Tested THD < 3% at full load.', 'images/proj_inverter.jpg', 'https://github.com/alexreyes/3phase-inverter', NULL, 'MATLAB,Simulink,PCB,IGBT,Power Electronics', 0, 4, '2026-06-03 02:15:45'),
(11, 'Substation HMI Dashboard', 'Power Systems', 'Built a responsive SCADA HMI web app (PHP + MySQL + Chart.js) for a 69kV substation. Displays real-time analog values, breaker status, and alarm logs. Role-based access control.', 'images/proj_hmi.jpg', '#', NULL, 'PHP,MySQL,Chart.js,SCADA,HMI', 0, 5, '2026-06-03 02:15:45'),
(12, 'Low-Power Air Quality Sensor', 'Embedded Systems', 'Ultra low-power (< 15µA sleep) air quality node using nRF52840 + SHT40 + SPS30 particulate sensor. BLE advertising with custom GATT profile. 2-year coin cell battery life achieved.', 'images/proj_aq.jpg', 'https://github.com/alexreyes/aq-sensor', NULL, 'nRF52840,BLE,Low Power,C,IoT', 0, 6, '2026-06-03 02:15:45');

-- --------------------------------------------------------

--
-- Table structure for table `skills`
--

CREATE TABLE `skills` (
  `id` int(11) NOT NULL,
  `category` varchar(80) NOT NULL,
  `name` varchar(100) NOT NULL,
  `level` tinyint(4) NOT NULL DEFAULT 80 COMMENT '0-100 percentage',
  `icon` varchar(80) DEFAULT NULL COMMENT 'Tabler icon class e.g. ti-cpu',
  `sort_order` tinyint(4) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `skills`
--

INSERT INTO `skills` (`id`, `category`, `name`, `level`, `icon`, `sort_order`) VALUES
(1, 'Hardware Design', 'PCB Design (KiCad / Altium)', 90, 'ti-cpu', 1),
(2, 'Hardware Design', 'Circuit Analysis & Simulation', 88, 'ti-wave-square', 2),
(3, 'Hardware Design', 'Power Electronics', 85, 'ti-bolt', 3),
(4, 'Hardware Design', 'FPGA / VHDL', 72, 'ti-device-desktop', 4),
(5, 'Embedded Systems', 'C / C++ Firmware', 92, 'ti-code', 5),
(6, 'Embedded Systems', 'Arduino / STM32 / ESP32', 90, 'ti-device-gamepad', 6),
(7, 'Embedded Systems', 'RTOS (FreeRTOS)', 78, 'ti-clock', 7),
(8, 'Embedded Systems', 'IoT Protocols (MQTT, BLE)', 80, 'ti-wifi', 8),
(9, 'Power Systems', 'Substation Automation', 85, 'ti-building-factory', 9),
(10, 'Power Systems', 'SCADA / PLC Programming', 82, 'ti-adjustments', 10),
(11, 'Power Systems', 'Load Flow Analysis (ETAP)', 80, 'ti-chart-line', 11),
(12, 'Software & Tools', 'Python (NumPy, SciPy)', 88, 'ti-brand-python', 12),
(13, 'Software & Tools', 'MATLAB / Simulink', 85, 'ti-math-function', 13),
(14, 'Software & Tools', 'Git / GitHub', 90, 'ti-brand-github', 14),
(15, 'Hardware Design', 'PCB Design (KiCad / Altium)', 90, 'ti-cpu', 1),
(16, 'Hardware Design', 'Circuit Analysis & Simulation', 88, 'ti-wave-square', 2),
(17, 'Hardware Design', 'Power Electronics', 85, 'ti-bolt', 3),
(18, 'Hardware Design', 'FPGA / VHDL', 72, 'ti-device-desktop', 4),
(19, 'Embedded Systems', 'C / C++ Firmware', 92, 'ti-code', 5),
(20, 'Embedded Systems', 'Arduino / STM32 / ESP32', 90, 'ti-device-gamepad', 6),
(21, 'Embedded Systems', 'RTOS (FreeRTOS)', 78, 'ti-clock', 7),
(22, 'Embedded Systems', 'IoT Protocols (MQTT, BLE)', 80, 'ti-wifi', 8),
(23, 'Power Systems', 'Substation Automation', 85, 'ti-building-factory', 9),
(24, 'Power Systems', 'SCADA / PLC Programming', 82, 'ti-adjustments', 10),
(25, 'Power Systems', 'Load Flow Analysis (ETAP)', 80, 'ti-chart-line', 11),
(26, 'Software & Tools', 'Python (NumPy, SciPy)', 88, 'ti-brand-python', 12),
(27, 'Software & Tools', 'MATLAB / Simulink', 85, 'ti-math-function', 13),
(28, 'Software & Tools', 'Git / GitHub', 90, 'ti-brand-github', 14);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `certifications`
--
ALTER TABLE `certifications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `contact_messages`
--
ALTER TABLE `contact_messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `education`
--
ALTER TABLE `education`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `experience`
--
ALTER TABLE `experience`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `profile`
--
ALTER TABLE `profile`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `projects`
--
ALTER TABLE `projects`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `skills`
--
ALTER TABLE `skills`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `certifications`
--
ALTER TABLE `certifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `contact_messages`
--
ALTER TABLE `contact_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `education`
--
ALTER TABLE `education`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `experience`
--
ALTER TABLE `experience`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `profile`
--
ALTER TABLE `profile`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `projects`
--
ALTER TABLE `projects`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `skills`
--
ALTER TABLE `skills`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
