-- phpMyAdmin SQL Dump
-- version 4.6.6deb5
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Gegenereerd op: 14 okt 2019 om 21:36
-- Serverversie: 5.7.26-0ubuntu0.18.10.1
-- PHP-versie: 7.2.19-0ubuntu0.18.10.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `leenfiets`
--

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `fietsen`
--

CREATE TABLE `fietsen` (
  `id` int(11) NOT NULL,
  `borg` decimal(11,2) NOT NULL,
  `prijs` decimal(11,2) NOT NULL,
  `versnellingen` int(11) NOT NULL,
  `gebruiker_id` int(11) NOT NULL,
  `plaats` varchar(255) NOT NULL,
  `id_soort_fiets` int(11) NOT NULL,
  `id_merk_fiets` int(11) NOT NULL,
  `kleur_fiets` varchar(255) NOT NULL,
  `geslacht_fiets` varchar(255) NOT NULL,
  `model` varchar(255) NOT NULL COMMENT 'Model van de fiets',
  `adres` varchar(255) NOT NULL COMMENT 'Adres van de verlener',
  `omschrijving` text COMMENT 'Omschrijving van de fiets',
  `foto` varchar(255) DEFAULT NULL COMMENT 'Pad naar de afbeelding van de fiets',
  `status` varchar(255) NOT NULL DEFAULT 'beschikbaar' COMMENT 'Waarde kan zijn, beschikbaar, niet beschikbaar, uitgeleend',
  `token` varchar(255) DEFAULT NULL COMMENT 'Deze token is nodig voor het QR systeem'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Gegevens worden geëxporteerd voor tabel `fietsen`
--

INSERT INTO `fietsen` (`id`, `borg`, `prijs`, `versnellingen`, `gebruiker_id`, `plaats`, `id_soort_fiets`, `id_merk_fiets`, `kleur_fiets`, `geslacht_fiets`, `model`, `adres`, `omschrijving`, `foto`, `status`, `token`) VALUES
(147, '150.00', '12.00', 6, 66, 'Assen', 15, 4, 'Geel', 'Vrouw', 'man', 'Iemstukken, 635', '', '', 'beschikbaar', NULL),
(149, '150.00', '13.00', 16, 66, 'groningen', 12, 4, 'Blauw', 'Man', 'ATB', 'Iemstukken 10', '', '', 'beschikbaar', NULL),
(151, '0.02', '0.80', 27, 65, 'Assen', 14, 4, 'Wit', 'Man', 'Bmw m5', 'Zuringes', NULL, '', 'beschikbaar', NULL),
(158, '30.00', '10.00', 10, 58, 'Groningen', 10, 1, 'Geel', 'Man', 'Racer', 'Hoofdstraat', 'Beste,\r<br>\r<br>De fiets is beschikbaar op maandag en dinsdag.\r<br>\r<br>Met vriendelijke groet,\r<br>Anton', '', 'beschikbaar', NULL),
(167, '10.00', '10.00', 10, 58, 'fcknj', 6, 4, 'Geel', 'Man', 'fa', 'kjc', '10sfaa', 'fiets_afbeeldingen/1110201916014958123.jpg', 'beschikbaar', NULL);

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `gebruiker`
--

CREATE TABLE `gebruiker` (
  `id` int(11) NOT NULL,
  `naam` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `bio` text,
  `wachtwoord` varchar(255) NOT NULL,
  `status_code` int(11) NOT NULL DEFAULT '0',
  `verificatiecode` varchar(255) NOT NULL,
  `wachtwoordcode` varchar(255) DEFAULT NULL,
  `nieuwe_wachtwoord` varchar(255) DEFAULT NULL,
  `rol` varchar(10) NOT NULL DEFAULT 'standaard'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Gegevens worden geëxporteerd voor tabel `gebruiker`
--

INSERT INTO `gebruiker` (`id`, `naam`, `email`, `bio`, `wachtwoord`, `status_code`, `verificatiecode`, `wachtwoordcode`, `nieuwe_wachtwoord`, `rol`) VALUES
(58, 'antonbonder', 'anton.bonder@hotmail.com', 'test', '$2y$10$XKmBqxtSBkcXryUbMFj1Iuv.lI6vQZH.9Fju294u0OG8E.2nlQ/c2', 1, 'dd8c50927b5424d33b2c45b85cf09fa2', '41bafc2e50be7239c3bc5518d86bfa3b', '$2y$10$ZYrSBB7m6bLYlzGpO8yZyenHYIZwss87e6Yx/gWwgneEbf3Sbj832', 'admin'),
(61, 'ruben', 'games.ruben01@gmail.com', '', '$2y$10$6mCQVJRNsPj3DIp.OH4tRO5LET/RZm1A.VwLR238WtKzhJa6gxhnW', 0, '25ff8cff7447f0f3233bd4c7a46ebc96', NULL, NULL, 'standaard'),
(62, 'bas', 'b.l.heijne@gmail.com', '', '$2y$10$NCXCY4TwCLVsFG2y8v8.4OzOBrMPxvOX/rzE18i1GM/VZrYPK4lra', 0, '10ef567a344bce8ac362c987d9a49a3e', NULL, NULL, 'standaard'),
(63, 'Thom Westra', 'th.h.westra@st.hanze.nl', '', '$2y$10$Xn5wcQFPHEEB6wMZPLTyEeaWaal5fdBdc9.KzjZF4ehi2HlueESJC', 1, '7bd33470e87e18bbee8ae9945a6ee0cd', NULL, NULL, 'admin'),
(64, 'a.bakema@st.hanze.nl', 'a.bakema@st.hanze.nl', '', '$2y$10$ntvGVRqj/zZUspg85wrgHO3jinb6VCCqbUeEF3lzhCj7h6mK1l4/e', 1, '11fa7a25b0601c22799f3fc89b12cb41', NULL, NULL, 'standaard'),
(65, 'Arjan', 'arjan123QQ@gmail.com', 'Grgrvrvrvev', '$2y$10$jgUBMet2Ea6l0ZSuU6pYherOJm40dUo/xXQrnButPvmk70Vf7xbkq', 1, '127f2a4bde7b1700f94479f8066ac821', NULL, NULL, 'admin'),
(66, 'rubeneekhof', 'rubeneekhof@gmail.com', '', '$2y$10$R9LEwby6Wjsn4Ocp9Y5HkOfpAJewZxrEFCTpqzpMPGNuevz7TmOBm', 1, '24bc09acbee923d95dd66bd203628000', NULL, NULL, 'admin'),
(67, 'marjolein', 'marjoleingernaat@hotmail.com', NULL, '$2y$10$Dbbzwh/1yp7HhNNDHD/fmuJBdQ0OhHpmkT.t1QNeAT7YZKV8FP2aC', 1, '86620908625a6cf0857f3cb2790f977a', NULL, NULL, 'standaard'),
(68, 'Tim Bunk', 'timbunk12@kpnmail.nl', 'Dit is Tim', '$2y$10$4Fwc.VnLYpCZ..VFvNdVcOQCvGWugbhmu0S5h0lHYSNAsZZEmH1ji', 1, '76dff61ed203834892d2002e657ed895', NULL, NULL, 'admin'),
(69, 'test', 'bla@gmail.com', NULL, '$2y$10$sZkHRjbYfdzArYVX.Pz4Se0de9j2qqnESCcNVa4C1tW67jh.y7ACW', 0, '88290b07ccf4c92d134562839ca956c6', NULL, NULL, 'standaard'),
(70, 'Audi', '1@gmail.com', '', '$2y$10$z7LxyInai3q4T.gne4Kw1OQzixF0K4oQCqzBhsw5FoyvAzudzTfR.', 1, '9d8d785af21e90ad989400d0d457f7f5', NULL, NULL, 'admin'),
(71, 'anton', 'a.d.bonder@st.hanze.nl', NULL, '$2y$10$ZbgC44amCK4m6D6oxqPlr.ww0lYwjg/OCVE/nNpUsVOAbTaJtafx.', 1, '601b5c8ef8d2326e4545191e11f68422', NULL, NULL, 'standaard'),
(72, 'ruben3', 'r.j.eekhof@st.hanze.nl', NULL, '$2y$10$7ORTZZ8DO6TRY8.tDnPDgOJIFa8qB4GUsDD5WuQnk/0.Ww93XZCeW', 1, 'ea619afdc476b1eaf1b914d0e7aaa719', NULL, NULL, 'standaard');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `klacht`
--

CREATE TABLE `klacht` (
  `aanbrenger` int(11) NOT NULL,
  `betreffende` int(11) DEFAULT NULL,
  `klacht` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `leen_geschiedenis`
--

CREATE TABLE `leen_geschiedenis` (
  `lener` int(11) DEFAULT NULL,
  `fiets_id` int(11) NOT NULL,
  `status` varchar(255) NOT NULL COMMENT 'Status kan zijn, in gebruik of teruggebracht',
  `ophaal_datum` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `terug_datum` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `merk_fiets`
--

CREATE TABLE `merk_fiets` (
  `id` int(11) NOT NULL,
  `merk_naam` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Gegevens worden geëxporteerd voor tabel `merk_fiets`
--

INSERT INTO `merk_fiets` (`id`, `merk_naam`) VALUES
(1, 'Gazelle'),
(2, 'Giant'),
(3, 'Cube'),
(4, 'Anders');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `nieuws`
--

CREATE TABLE `nieuws` (
  `schrijver` int(11) NOT NULL,
  `titel` varchar(255) NOT NULL,
  `bericht` text NOT NULL,
  `datum` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `foto` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `review`
--

CREATE TABLE `review` (
  `gebruiker_id` int(11) NOT NULL,
  `fiets_id` int(11) NOT NULL,
  `titel` varchar(255) NOT NULL,
  `review` text NOT NULL,
  `sterren` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `soort_fiets`
--

CREATE TABLE `soort_fiets` (
  `id` int(11) NOT NULL,
  `soort_fiets` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Gegevens worden geëxporteerd voor tabel `soort_fiets`
--

INSERT INTO `soort_fiets` (`id`, `soort_fiets`) VALUES
(1, 'Omafiets'),
(2, 'Elektrische fiets'),
(3, 'Mountainbike'),
(4, 'Kinderfiets'),
(5, 'Moederfiets'),
(6, 'Bakfiets'),
(7, 'Hybride fiets'),
(8, 'Vouwfiets'),
(9, 'Stadsfiets'),
(10, 'Beach cruisers'),
(11, 'Tandem'),
(12, 'Ligfiets'),
(13, 'BMX fiets'),
(14, 'Eenwieler'),
(15, 'Anders');

--
-- Indexen voor geëxporteerde tabellen
--

--
-- Indexen voor tabel `fietsen`
--
ALTER TABLE `fietsen`
  ADD PRIMARY KEY (`id`),
  ADD KEY `gebruiker_id` (`gebruiker_id`),
  ADD KEY `foto_id` (`foto`),
  ADD KEY `id_soort_fiets` (`id_soort_fiets`),
  ADD KEY `id_merk_fiets` (`id_merk_fiets`);

--
-- Indexen voor tabel `gebruiker`
--
ALTER TABLE `gebruiker`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexen voor tabel `klacht`
--
ALTER TABLE `klacht`
  ADD KEY `aanbrenger` (`aanbrenger`),
  ADD KEY `betreffende` (`betreffende`);

--
-- Indexen voor tabel `leen_geschiedenis`
--
ALTER TABLE `leen_geschiedenis`
  ADD KEY `lener` (`lener`),
  ADD KEY `fiets_id` (`fiets_id`);

--
-- Indexen voor tabel `merk_fiets`
--
ALTER TABLE `merk_fiets`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `nieuws`
--
ALTER TABLE `nieuws`
  ADD KEY `schrijver` (`schrijver`);

--
-- Indexen voor tabel `review`
--
ALTER TABLE `review`
  ADD KEY `gebruiker_id` (`gebruiker_id`),
  ADD KEY `fiets_id` (`fiets_id`);

--
-- Indexen voor tabel `soort_fiets`
--
ALTER TABLE `soort_fiets`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT voor geëxporteerde tabellen
--

--
-- AUTO_INCREMENT voor een tabel `fietsen`
--
ALTER TABLE `fietsen`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=170;
--
-- AUTO_INCREMENT voor een tabel `gebruiker`
--
ALTER TABLE `gebruiker`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=73;
--
-- AUTO_INCREMENT voor een tabel `merk_fiets`
--
ALTER TABLE `merk_fiets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
--
-- AUTO_INCREMENT voor een tabel `soort_fiets`
--
ALTER TABLE `soort_fiets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;
--
-- Beperkingen voor geëxporteerde tabellen
--

--
-- Beperkingen voor tabel `fietsen`
--
ALTER TABLE `fietsen`
  ADD CONSTRAINT `fietsen_ibfk_1` FOREIGN KEY (`gebruiker_id`) REFERENCES `gebruiker` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fietsen_ibfk_2` FOREIGN KEY (`id_soort_fiets`) REFERENCES `soort_fiets` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fietsen_ibfk_3` FOREIGN KEY (`id_merk_fiets`) REFERENCES `merk_fiets` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Beperkingen voor tabel `klacht`
--
ALTER TABLE `klacht`
  ADD CONSTRAINT `klacht_ibfk_1` FOREIGN KEY (`aanbrenger`) REFERENCES `gebruiker` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `klacht_ibfk_2` FOREIGN KEY (`betreffende`) REFERENCES `gebruiker` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Beperkingen voor tabel `leen_geschiedenis`
--
ALTER TABLE `leen_geschiedenis`
  ADD CONSTRAINT `leen_geschiedenis_ibfk_1` FOREIGN KEY (`lener`) REFERENCES `gebruiker` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `leen_geschiedenis_ibfk_2` FOREIGN KEY (`fiets_id`) REFERENCES `fietsen` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Beperkingen voor tabel `nieuws`
--
ALTER TABLE `nieuws`
  ADD CONSTRAINT `nieuws_ibfk_1` FOREIGN KEY (`schrijver`) REFERENCES `gebruiker` (`id`) ON UPDATE CASCADE;

--
-- Beperkingen voor tabel `review`
--
ALTER TABLE `review`
  ADD CONSTRAINT `review_ibfk_1` FOREIGN KEY (`gebruiker_id`) REFERENCES `gebruiker` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `review_ibfk_2` FOREIGN KEY (`fiets_id`) REFERENCES `fietsen` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
