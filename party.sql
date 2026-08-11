-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Creato il: Ago 11, 2026 alle 11:08
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
-- Struttura della tabella `accounts`
--

CREATE TABLE `accounts` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `accounts`
--

INSERT INTO `accounts` (`id`, `username`, `password`) VALUES
(2, 'damares86', '$2y$10$h2Xug/pqhbLKZ6w2uPlC3OnEmm1qcddEXgAjEIpvNuOwKT7Es.8JS');

-- --------------------------------------------------------

--
-- Struttura della tabella `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `place_id` int(5) DEFAULT NULL,
  `order_number` int(10) NOT NULL,
  `bill` decimal(6,2) DEFAULT NULL,
  `paid` int(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `orders`
--

INSERT INTO `orders` (`id`, `email`, `place_id`, `order_number`, `bill`, `paid`) VALUES
(37, 'economo@agnelli.it', 9, 2, 8.00, 0);

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
(44, 34, 1, 'R', 4, 0),
(45, 34, 2, 'E', 1, 0),
(46, 35, 1, 'M', 3, 0),
(47, 36, 2, 'G', 1, 0),
(48, 36, 1, 'I', 1, 0),
(49, 37, 1, 'Z', 1, 0),
(50, 37, 2, 'N', 2, 0);

-- --------------------------------------------------------

--
-- Struttura della tabella `place`
--

CREATE TABLE `place` (
  `id` int(5) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `place`
--

INSERT INTO `place` (`id`, `name`) VALUES
(1, 'Scuola media'),
(2, 'Liceo'),
(3, 'ITT'),
(4, 'Oratorio'),
(5, 'Parrocchia'),
(6, 'Cnos-Fap'),
(7, 'Housing'),
(8, 'Cinema'),
(9, 'Dipendenti');

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
-- Indici per le tabelle `accounts`
--
ALTER TABLE `accounts`
  ADD PRIMARY KEY (`id`);

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
-- Indici per le tabelle `place`
--
ALTER TABLE `place`
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
-- AUTO_INCREMENT per la tabella `accounts`
--
ALTER TABLE `accounts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT per la tabella `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT per la tabella `orders_details`
--
ALTER TABLE `orders_details`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT per la tabella `place`
--
ALTER TABLE `place`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT per la tabella `products`
--
ALTER TABLE `products`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
