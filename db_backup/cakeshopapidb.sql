-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 13, 2025 at 08:28 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `cakeshopapidb`
--

-- --------------------------------------------------------

--
-- Table structure for table `cakes`
--

CREATE TABLE `cakes` (
  `id` varchar(20) NOT NULL,
  `name` varchar(100) NOT NULL,
  `category_id` varchar(20) DEFAULT NULL,
  `flavour_id` varchar(20) DEFAULT NULL,
  `weight_kg` decimal(5,2) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `description` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cakes`
--

INSERT INTO `cakes` (`id`, `name`, `category_id`, `flavour_id`, `weight_kg`, `price`, `description`, `image`, `created_at`) VALUES
('CAKE-3', 'Chocolate Truffle', 'CAT-1', 'FLAV-1', 0.50, 399.00, 'Rich chocolate cake', '67f50e43eecef_img_x500_62840f1ac48685-09568429-13424217-removebg-preview.png', '2025-04-08 17:23:39'),
('CAKE-4', 'caremal Forest', 'CAT-1', 'FLAV-1', 4.00, 1000.00, 'a rich and decadent layered cake known for its distinctive ingredients and flavors', 'uploads/67f516baa1bb8_Pink-Cake.jpeg', '2025-04-08 17:34:50'),
('CAKE-5', 'Black Forest', 'CAT-1', 'FLAV-1', 1.00, 500.00, 'a rich and decadent layered cake known for its distinctive ingredients and flavors', '67f51120508f2_images (2).jpeg', '2025-04-08 17:35:52'),
('CAKE-6', 'white Forest', 'CAT-1', 'FLAV-1', 4.00, 1500.00, 'a rich and decadent layered cake known for its distinctive ingredients and flavors', '67f5167b0edaa_—Pngtree—colorful candy birthday cake with_16620345.png', '2025-04-08 17:58:43');

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id` varchar(20) NOT NULL,
  `user_id` varchar(20) DEFAULT NULL,
  `cake_id` varchar(20) DEFAULT NULL,
  `quantity` int(11) DEFAULT 1,
  `added_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` varchar(20) NOT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`) VALUES
('CAT-1', 'Classic Cakes'),
('CAT-2', 'Fruit Cakes');

-- --------------------------------------------------------

--
-- Table structure for table `flavours`
--

CREATE TABLE `flavours` (
  `id` varchar(20) NOT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `flavours`
--

INSERT INTO `flavours` (`id`, `name`) VALUES
('FLAV-1', 'Vanilla'),
('FLAV-2', 'Chocolate'),
('FLAV-3', 'Mango');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` varchar(20) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('user','admin') DEFAULT 'user',
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cakes`
--
ALTER TABLE `cakes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`),
  ADD KEY `flavour_id` (`flavour_id`);

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `cake_id` (`cake_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `flavours`
--
ALTER TABLE `flavours`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cakes`
--
ALTER TABLE `cakes`
  ADD CONSTRAINT `cakes_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`),
  ADD CONSTRAINT `cakes_ibfk_2` FOREIGN KEY (`flavour_id`) REFERENCES `flavours` (`id`);

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`cake_id`) REFERENCES `cakes` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
