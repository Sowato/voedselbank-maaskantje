-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 03, 2026 at 09:31 AM
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
-- Database: `maaskantje`
--

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `id` int(11) NOT NULL,
  `code` varchar(25) NOT NULL,
  `omschrijving` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `inhoud`
--

CREATE TABLE `inhoud` (
  `id` int(11) NOT NULL,
  `pakket_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `aantal` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `klant`
--

CREATE TABLE `klant` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `gezins_naam` varchar(255) NOT NULL,
  `plaats` varchar(255) DEFAULT NULL,
  `adres` varchar(255) DEFAULT NULL,
  `telefoon` varchar(255) NOT NULL,
  `volwassen` int(11) NOT NULL,
  `kind` int(11) DEFAULT NULL,
  `baby` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `klant_wens`
--

CREATE TABLE `klant_wens` (
  `klant_id` int(11) NOT NULL,
  `wens_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `leverancier`
--

CREATE TABLE `leverancier` (
  `id` int(11) NOT NULL,
  `company` varchar(255) NOT NULL,
  `adres` varchar(255) DEFAULT NULL,
  `plaats` varchar(255) DEFAULT NULL,
  `contact_persoon` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `telefoon` varchar(25) NOT NULL,
  `volgende_levering_datum` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `levering`
--

CREATE TABLE `levering` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `leverancier_id` int(11) NOT NULL,
  `datumtijd` datetime NOT NULL,
  `aantal` double NOT NULL,
  `houdbaar_tot` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `messenger_messages`
--

CREATE TABLE `messenger_messages` (
  `id` bigint(20) NOT NULL,
  `body` longtext NOT NULL,
  `headers` longtext NOT NULL,
  `queue_name` varchar(190) NOT NULL,
  `created_at` datetime NOT NULL,
  `available_at` datetime NOT NULL,
  `delivered_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pakket`
--

CREATE TABLE `pakket` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `klant_id` int(11) NOT NULL,
  `datum` datetime NOT NULL,
  `uitgifte_datum` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

CREATE TABLE `product` (
  `id` int(11) NOT NULL,
  `category_id` int(11) DEFAULT NULL,
  `streepjescode` varchar(255) NOT NULL,
  `omschrijving` varchar(255) NOT NULL,
  `aantal` double DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `email` varchar(180) NOT NULL,
  `roles` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `password` varchar(255) NOT NULL
) ;

-- --------------------------------------------------------

--
-- Table structure for table `wens`
--

CREATE TABLE `wens` (
  `id` int(11) NOT NULL,
  `omschrijving` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `inhoud`
--
ALTER TABLE `inhoud`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_1DB54D1ECDE6430C` (`pakket_id`),
  ADD KEY `IDX_1DB54D1E4584665A` (`product_id`);

--
-- Indexes for table `klant`
--
ALTER TABLE `klant`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `klant_wens`
--
ALTER TABLE `klant_wens`
  ADD PRIMARY KEY (`klant_id`,`wens_id`),
  ADD KEY `IDX_176492503C427B2F` (`klant_id`),
  ADD KEY `IDX_176492502A12754E` (`wens_id`);

--
-- Indexes for table `leverancier`
--
ALTER TABLE `leverancier`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `levering`
--
ALTER TABLE `levering`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_19D93554A76ED395` (`user_id`),
  ADD KEY `IDX_19D935544584665A` (`product_id`),
  ADD KEY `IDX_19D935546E3FE6C9` (`leverancier_id`);

--
-- Indexes for table `messenger_messages`
--
ALTER TABLE `messenger_messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_75EA56E0FB7336F0` (`queue_name`),
  ADD KEY `IDX_75EA56E0E3BD61CE` (`available_at`),
  ADD KEY `IDX_75EA56E016BA31DB` (`delivered_at`);

--
-- Indexes for table `pakket`
--
ALTER TABLE `pakket`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_F9F58C9BA76ED395` (`user_id`),
  ADD KEY `IDX_F9F58C9B3C427B2F` (`klant_id`);

--
-- Indexes for table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_D34A04AD12469DE2` (`category_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_8D93D649E7927C74` (`email`);

--
-- Indexes for table `wens`
--
ALTER TABLE `wens`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `inhoud`
--
ALTER TABLE `inhoud`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `klant`
--
ALTER TABLE `klant`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `leverancier`
--
ALTER TABLE `leverancier`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `levering`
--
ALTER TABLE `levering`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `messenger_messages`
--
ALTER TABLE `messenger_messages`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pakket`
--
ALTER TABLE `pakket`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `product`
--
ALTER TABLE `product`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wens`
--
ALTER TABLE `wens`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `inhoud`
--
ALTER TABLE `inhoud`
  ADD CONSTRAINT `FK_1DB54D1E4584665A` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`),
  ADD CONSTRAINT `FK_1DB54D1ECDE6430C` FOREIGN KEY (`pakket_id`) REFERENCES `pakket` (`id`);

--
-- Constraints for table `klant_wens`
--
ALTER TABLE `klant_wens`
  ADD CONSTRAINT `FK_176492502A12754E` FOREIGN KEY (`wens_id`) REFERENCES `wens` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_176492503C427B2F` FOREIGN KEY (`klant_id`) REFERENCES `klant` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `levering`
--
ALTER TABLE `levering`
  ADD CONSTRAINT `FK_19D935544584665A` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`),
  ADD CONSTRAINT `FK_19D935546E3FE6C9` FOREIGN KEY (`leverancier_id`) REFERENCES `leverancier` (`id`),
  ADD CONSTRAINT `FK_19D93554A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Constraints for table `pakket`
--
ALTER TABLE `pakket`
  ADD CONSTRAINT `FK_F9F58C9B3C427B2F` FOREIGN KEY (`klant_id`) REFERENCES `klant` (`id`),
  ADD CONSTRAINT `FK_F9F58C9BA76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Constraints for table `product`
--
ALTER TABLE `product`
  ADD CONSTRAINT `FK_D34A04AD12469DE2` FOREIGN KEY (`category_id`) REFERENCES `category` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
