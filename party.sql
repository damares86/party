-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Creato il: Lug 08, 2026 alle 16:27
-- Versione del server: 10.4.32-MariaDB
-- Versione PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `party`
--

-- --------------------------------------------------------

--
-- Struttura della tabella `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `place` varchar(255) DEFAULT NULL,
  `order_code` int(10) NOT NULL,
  `letter_code` varchar(5) NOT NULL,
  `paid` int(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `orders`
--

INSERT INTO `orders` (`id`, `email`, `place`, `order_code`, `letter_code`, `paid`) VALUES
(10, 'economo@agnelli.it', NULL, 3, 'G', 0),
(11, 'economo@agnelli.it', NULL, 4, 'X', 0),
(12, 'economo@agnelli.it', NULL, 5, 'X', 0),
(13, 'economo@agnelli.it', NULL, 6, 'G', 0),
(14, 'economo@agnelli.it', NULL, 7, 'A', 0),
(15, 'davidemasera@gmail.com', NULL, 8, 'C', 0),
(16, 'davidemasera@gmail.com', NULL, 9, 'H', 0),
(17, 'davidemasera@gmail.com', NULL, 10, 'M', 0),
(18, 'davidemasera@gmail.com', NULL, 11, 'C', 0),
(19, 'davidemasera@gmail.com', NULL, 12, 'P', 0),
(20, 'davidemasera@gmail.com', NULL, 13, 'K', 0),
(21, 'davidemasera@gmail.com', NULL, 14, 'K', 0),
(22, 'davidemasera@gmail.com', NULL, 15, 'W', 0),
(23, 'davidemasera@gmail.com', NULL, 16, 'D', 0),
(24, 'davidemasera@gmail.com', NULL, 17, 'B', 0);

-- --------------------------------------------------------

--
-- Struttura della tabella `orders_details`
--

CREATE TABLE `orders_details` (
  `id` int(5) NOT NULL,
  `orders_id` int(5) NOT NULL,
  `products_id` int(5) NOT NULL,
  `qty` int(5) NOT NULL,
  `used` int(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `orders_details`
--

INSERT INTO `orders_details` (`id`, `orders_id`, `products_id`, `qty`, `used`) VALUES
(1, 12, 1, 3, 0),
(2, 13, 1, 3, 0),
(3, 13, 2, 4, 0),
(4, 14, 2, 1, 0),
(5, 15, 1, 3, 0),
(6, 15, 2, 5, 0),
(7, 16, 1, 3, 0),
(8, 16, 2, 5, 0),
(9, 17, 1, 3, 0),
(10, 17, 2, 5, 0),
(11, 18, 1, 3, 0),
(12, 18, 2, 5, 0),
(13, 19, 1, 3, 0),
(14, 19, 2, 5, 0),
(15, 20, 1, 3, 0),
(16, 20, 2, 5, 0),
(17, 21, 1, 3, 0),
(18, 21, 2, 5, 0),
(19, 22, 1, 3, 0),
(20, 22, 2, 5, 0),
(21, 23, 1, 3, 0),
(22, 23, 2, 5, 0),
(23, 24, 1, 3, 0),
(24, 24, 2, 5, 0);

-- --------------------------------------------------------

--
-- Struttura della tabella `products`
--

CREATE TABLE `products` (
  `id` int(5) NOT NULL,
  `name` varchar(255) NOT NULL,
  `code` varchar(255) NOT NULL,
  `price` decimal(5,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `products`
--

INSERT INTO `products` (`id`, `name`, `code`, `price`) VALUES
(1, 'Patatine', 'PAT', 3.00),
(2, 'Salsiccia', 'SAL', 2.50);

--
-- Indici per le tabelle scaricate
--

--
-- Indici per le tabelle `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Indici per le tabelle `orders_details`
--
ALTER TABLE `orders_details`
  ADD PRIMARY KEY (`id`);

--
-- Indici per le tabelle `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT per le tabelle scaricate
--

--
-- AUTO_INCREMENT per la tabella `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT per la tabella `orders_details`
--
ALTER TABLE `orders_details`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT per la tabella `products`
--
ALTER TABLE `products`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
