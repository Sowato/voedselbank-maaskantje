-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Gegenereerd op: 03 jun 2026 om 15:01
-- Serverversie: 10.4.32-MariaDB
-- PHP-versie: 8.2.12

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
-- Tabelstructuur voor tabel `category`
--

CREATE TABLE `category` (
  `id` int(11) NOT NULL,
  `code` varchar(25) NOT NULL,
  `omschrijving` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Gegevens worden geëxporteerd voor tabel `category`
--

INSERT INTO `category` (`id`, `code`, `omschrijving`) VALUES
(1, 'DIEPVRIES', 'diepvriesproducten'),
(11, 'AARD', 'Aardappelen'),
(12, 'GROEN', 'Groenten'),
(13, 'FRUIT', 'Fruit'),
(14, 'KAAS', 'Kaas'),
(15, 'VLEES', 'Vleeswaren'),
(16, 'ZUIV', 'Zuivel'),
(17, 'PLANT', 'Plantaardig'),
(18, 'EIER', 'Eieren'),
(19, 'BAK', 'Bakkerij'),
(20, 'BANK', 'Banket'),
(21, 'FRIS', 'Frisdrank'),
(22, 'SAP', 'Sappen'),
(23, 'KOF', 'Koffie en thee'),
(24, 'PASTA', 'Pasta'),
(25, 'RIJST', 'Rijst'),
(26, 'WORLD', 'Wereldkeuken'),
(27, 'SOEP', 'Soepen'),
(28, 'SAUS', 'Sauzen'),
(29, 'KRUID', 'Kruiden'),
(30, 'OLIE', 'Olie'),
(31, 'SNOEP', 'Snoep'),
(32, 'KOEK', 'Koek'),
(33, 'CHIPS', 'Chips'),
(34, 'CHOC', 'Chocolade'),
(35, 'BABY', 'Baby'),
(36, 'VERZ', 'Verzorging'),
(37, 'HYG', 'Hygiëne');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `inhoud`
--

CREATE TABLE `inhoud` (
  `id` int(11) NOT NULL,
  `pakket_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `aantal` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `klant`
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
-- Tabelstructuur voor tabel `klant_wens`
--

CREATE TABLE `klant_wens` (
  `klant_id` int(11) NOT NULL,
  `wens_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `leverancier`
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
-- Tabelstructuur voor tabel `levering`
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
-- Tabelstructuur voor tabel `messenger_messages`
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
-- Tabelstructuur voor tabel `pakket`
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
-- Tabelstructuur voor tabel `product`
--

CREATE TABLE `product` (
  `id` int(11) NOT NULL,
  `category_id` int(11) DEFAULT NULL,
  `streepjescode` varchar(255) NOT NULL,
  `omschrijving` varchar(255) NOT NULL,
  `aantal` double DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Gegevens worden geëxporteerd voor tabel `product`
--

INSERT INTO `product` (`id`, `category_id`, `streepjescode`, `omschrijving`, `aantal`) VALUES
(1, 11, '12329i4u305', 'patat', 1),
(2, 19, '32342565568', 'brood', 4);

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `email` varchar(180) NOT NULL,
  `roles` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Gegevens worden geëxporteerd voor tabel `user`
--

INSERT INTO `user` (`id`, `email`, `roles`, `password`) VALUES
(1, 'd@gmail.com', 'Admin\r\n', '$2y$10$NYXU2XEoq3JaW2.ZyeW89.qzAD4/fN8eukNM.UqAiUvZWgfPjTlKy'),
(2, 'dj@gmail.com', 'klant', '$2y$10$U4/5uh/dZTWbuQqn7wcE7.Pg2kjL8iL7dDIRDdYXYnXwIAmRLJaRa'),
(3, 'medewerker@gmail.com', 'medewerker', '$2y$10$LYJaD/M51ETxGOOHpuvUdeHjWPNT7vuOVy9tf23b45GzlbpEeV8B2');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `wens`
--

CREATE TABLE `wens` (
  `id` int(11) NOT NULL,
  `omschrijving` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexen voor geëxporteerde tabellen
--

--
-- Indexen voor tabel `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `inhoud`
--
ALTER TABLE `inhoud`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_1DB54D1ECDE6430C` (`pakket_id`),
  ADD KEY `IDX_1DB54D1E4584665A` (`product_id`);

--
-- Indexen voor tabel `klant`
--
ALTER TABLE `klant`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `klant_wens`
--
ALTER TABLE `klant_wens`
  ADD PRIMARY KEY (`klant_id`,`wens_id`),
  ADD KEY `IDX_176492503C427B2F` (`klant_id`),
  ADD KEY `IDX_176492502A12754E` (`wens_id`);

--
-- Indexen voor tabel `leverancier`
--
ALTER TABLE `leverancier`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `levering`
--
ALTER TABLE `levering`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_19D93554A76ED395` (`user_id`),
  ADD KEY `IDX_19D935544584665A` (`product_id`),
  ADD KEY `IDX_19D935546E3FE6C9` (`leverancier_id`);

--
-- Indexen voor tabel `messenger_messages`
--
ALTER TABLE `messenger_messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_75EA56E0FB7336F0` (`queue_name`),
  ADD KEY `IDX_75EA56E0E3BD61CE` (`available_at`),
  ADD KEY `IDX_75EA56E016BA31DB` (`delivered_at`);

--
-- Indexen voor tabel `pakket`
--
ALTER TABLE `pakket`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_F9F58C9BA76ED395` (`user_id`),
  ADD KEY `IDX_F9F58C9B3C427B2F` (`klant_id`);

--
-- Indexen voor tabel `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_D34A04AD12469DE2` (`category_id`);

--
-- Indexen voor tabel `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_8D93D649E7927C74` (`email`);

--
-- Indexen voor tabel `wens`
--
ALTER TABLE `wens`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT voor geëxporteerde tabellen
--

--
-- AUTO_INCREMENT voor een tabel `category`
--
ALTER TABLE `category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT voor een tabel `inhoud`
--
ALTER TABLE `inhoud`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `klant`
--
ALTER TABLE `klant`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `leverancier`
--
ALTER TABLE `leverancier`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `levering`
--
ALTER TABLE `levering`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `messenger_messages`
--
ALTER TABLE `messenger_messages`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `pakket`
--
ALTER TABLE `pakket`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `product`
--
ALTER TABLE `product`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT voor een tabel `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT voor een tabel `wens`
--
ALTER TABLE `wens`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Beperkingen voor geëxporteerde tabellen
--

--
-- Beperkingen voor tabel `inhoud`
--
ALTER TABLE `inhoud`
  ADD CONSTRAINT `FK_1DB54D1E4584665A` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`),
  ADD CONSTRAINT `FK_1DB54D1ECDE6430C` FOREIGN KEY (`pakket_id`) REFERENCES `pakket` (`id`);

--
-- Beperkingen voor tabel `klant_wens`
--
ALTER TABLE `klant_wens`
  ADD CONSTRAINT `FK_176492502A12754E` FOREIGN KEY (`wens_id`) REFERENCES `wens` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_176492503C427B2F` FOREIGN KEY (`klant_id`) REFERENCES `klant` (`id`) ON DELETE CASCADE;

--
-- Beperkingen voor tabel `levering`
--
ALTER TABLE `levering`
  ADD CONSTRAINT `FK_19D935544584665A` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`),
  ADD CONSTRAINT `FK_19D935546E3FE6C9` FOREIGN KEY (`leverancier_id`) REFERENCES `leverancier` (`id`),
  ADD CONSTRAINT `FK_19D93554A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Beperkingen voor tabel `pakket`
--
ALTER TABLE `pakket`
  ADD CONSTRAINT `FK_F9F58C9B3C427B2F` FOREIGN KEY (`klant_id`) REFERENCES `klant` (`id`),
  ADD CONSTRAINT `FK_F9F58C9BA76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Beperkingen voor tabel `product`
--
ALTER TABLE `product`
  ADD CONSTRAINT `FK_D34A04AD12469DE2` FOREIGN KEY (`category_id`) REFERENCES `category` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
