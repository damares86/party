-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Creato il: Ago 18, 2026 alle 11:56
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
(1, 'damares86', '$2y$10$h2Xug/pqhbLKZ6w2uPlC3OnEmm1qcddEXgAjEIpvNuOwKT7Es.8JS'),
(2, 'pagamento', '$2y$10$SHMkS2pKwAUra7L1Wehr8OSegiUOdXhTS8DX6udwdIV4D0si8JZgi'),
(5, 'cassa', '$2y$10$LL982FJnlCFDkuHcr6HHduOYtlpQUYJAQVcjX.cDseh5l2b99SpJ6');

-- --------------------------------------------------------

--
-- Struttura della tabella `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `place_id` int(5) DEFAULT NULL,
  `order_number` int(10) NOT NULL,
  `qty` int(5) NOT NULL,
  `bill` decimal(6,2) DEFAULT NULL,
  `paid` int(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `orders`
--

INSERT INTO `orders` (`id`, `email`, `place_id`, `order_number`, `qty`, `bill`, `paid`) VALUES
(42, 'economo@agnelli.it', 9, 2, 3, 15.00, 1);

-- --------------------------------------------------------

--
-- Struttura della tabella `orders_details`
--

CREATE TABLE `orders_details` (
  `id` int(5) NOT NULL,
  `orders_id` int(5) NOT NULL,
  `product_code` varchar(255) NOT NULL,
  `letter` varchar(1) NOT NULL,
  `qty` int(5) NOT NULL,
  `products_id` int(5) NOT NULL,
  `used` int(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `orders_details`
--

INSERT INTO `orders_details` (`id`, `orders_id`, `product_code`, `letter`, `qty`, `products_id`, `used`) VALUES
(6, 42, 'SAL', 'C', 3, 0, 1),
(7, 42, 'PAT', 'B', 3, 0, 0),
(8, 42, 'BEV', 'Y', 1, 1, 1),
(9, 42, 'BEV', 'T', 1, 2, 0),
(10, 42, 'BEV', 'L', 1, 6, 0);

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
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `products`
--

INSERT INTO `products` (`id`, `name`) VALUES
(1, 'Acqua naturale'),
(2, 'Acqua frizzante'),
(6, 'Coca cola'),
(8, 'Fanta'),
(10, 'Sprite');

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT per la tabella `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT per la tabella `orders_details`
--
ALTER TABLE `orders_details`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT per la tabella `place`
--
ALTER TABLE `place`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT per la tabella `products`
--
ALTER TABLE `products`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
