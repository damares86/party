-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Creato il: Lug 09, 2026 alle 15:06
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
  `order_number` int(10) NOT NULL,
  `paid` int(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `orders`
--

INSERT INTO `orders` (`id`, `email`, `place`, `order_number`, `paid`) VALUES
(25, 'economo@agnelli.it', NULL, 1, 0),
(26, 'economo@agnelli.it', NULL, 2, 0),
(27, 'economo@agnelli.it', NULL, 3, 0),
(28, 'economo@agnelli.it', NULL, 4, 0),
(29, 'economo@agnelli.it', NULL, 5, 0);

-- --------------------------------------------------------

--
-- Struttura della tabella `orders_details`
--

CREATE TABLE `orders_details` (
  `id` int(5) NOT NULL,
  `orders_id` int(5) NOT NULL,
  `products_id` int(5) NOT NULL,
  `product_letter` varchar(5) NOT NULL,
  `qty` int(5) NOT NULL,
  `used` int(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `orders_details`
--

INSERT INTO `orders_details` (`id`, `orders_id`, `products_id`, `product_letter`, `qty`, `used`) VALUES
(25, 25, 1, 'Z', 4, 0),
(26, 25, 2, 'C', 3, 0),
(27, 26, 1, 'Z', 4, 0),
(28, 26, 2, 'D', 3, 0),
(29, 27, 1, 'H', 4, 0),
(30, 27, 2, 'S', 3, 0),
(31, 27, 1, 'I', 1, 0),
(32, 28, 1, 'J', 4, 0),
(33, 28, 2, 'M', 5, 0),
(34, 29, 1, 'X', 4, 0),
(35, 29, 2, 'C', 4, 0),
(36, 29, 1, 'D', 2, 0);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT per la tabella `orders_details`
--
ALTER TABLE `orders_details`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT per la tabella `products`
--
ALTER TABLE `products`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
