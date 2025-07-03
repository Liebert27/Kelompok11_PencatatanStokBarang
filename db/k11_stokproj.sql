-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 03, 2025 at 04:29 AM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `k11_stokproj`
--

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `SelectAllItems` (IN `p_gudang_id` INT)   BEGIN
    SELECT *, calculate_item_total(stok_barang, harga) as total_harga 
    FROM items 
    WHERE gudang_id = p_gudang_id
    ORDER BY nama_barang;
END$$

--
-- Functions
--
CREATE DEFINER=`root`@`localhost` FUNCTION `calculate_item_total` (`stok` INT, `harga` DECIMAL(10,2)) RETURNS DECIMAL(10,2) DETERMINISTIC BEGIN
    RETURN stok * harga;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Stand-in structure for view `cardagr`
-- (See below for the actual view)
--
CREATE TABLE `cardagr` (
`gudang_id` varchar(7)
,`total_stock` decimal(32,0)
,`item_count` bigint(21)
,`total_value` decimal(42,2)
,`avg_harga` decimal(21,2)
);

-- --------------------------------------------------------

--
-- Table structure for table `items`
--

CREATE TABLE `items` (
  `id` int(11) NOT NULL,
  `gudang_id` varchar(7) DEFAULT NULL,
  `nama_barang` varchar(100) NOT NULL,
  `stok_barang` int(11) NOT NULL,
  `harga` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `items`
--

INSERT INTO `items` (`id`, `gudang_id`, `nama_barang`, `stok_barang`, `harga`) VALUES
(3, '2', 'Mie Sedap', 30, '4000.00');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `gudang_id` varchar(7) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `gudang_id`) VALUES
(1, 'ix', '$2y$10$NwbxmuPDxbGTV7C89IR0EuZAaKgCcxbTFSaIzPiEITkRgZJXEweFK', '2'),
(4, 'x', '$2y$10$q5pojmjnHEVO4JIGvz.kqOPKwZiJjaNzwn0fSZwkMyDmLWCKX3Tgm', '1');

-- --------------------------------------------------------

--
-- Structure for view `cardagr`
--
DROP TABLE IF EXISTS `cardagr`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `cardagr`  AS SELECT `items`.`gudang_id` AS `gudang_id`, sum(`items`.`stok_barang`) AS `total_stock`, count(`items`.`id`) AS `item_count`, sum(`items`.`stok_barang` * `items`.`harga`) AS `total_value`, round(avg(`items`.`stok_barang` * `items`.`harga`),2) AS `avg_harga` FROM `items` GROUP BY `items`.`gudang_id``gudang_id`  ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `gudang_id` (`gudang_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `gudang_id` (`gudang_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `items`
--
ALTER TABLE `items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `items`
--
ALTER TABLE `items`
  ADD CONSTRAINT `items_ibfk_1` FOREIGN KEY (`gudang_id`) REFERENCES `users` (`gudang_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
