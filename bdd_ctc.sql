-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 02, 2023 at 02:42 AM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+01:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `bdd_ctc`
--

-- --------------------------------------------------------

--
-- Table structure for table `administrateur`
--

CREATE TABLE `administrateur` (
  `idAdmin` int(11) NOT NULL,
  `nom` varchar(255) NOT NULL,
  `prenom` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `motdepasse` varchar(255) NOT NULL,
  `dateCreation` datetime NOT NULL DEFAULT current_timestamp(),
  `dernierLogin` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `administrateur`
--

INSERT INTO `administrateur` (`idAdmin`, `nom`, `prenom`, `email`, `motdepasse`, `dateCreation`, `dernierLogin`) VALUES
(1, 'Mnif', 'Ahmed', 'admin@ctc.com', '$2y$10$N2TyOafrFO4BQxSM3Zyy1elksbZV5IsHo23HkcA6xZYTahjVXrhJq', '2023-04-30 17:08:04', '2023-05-02 01:20:36');

-- --------------------------------------------------------

--
-- Table structure for table `client`
--

CREATE TABLE `client` (
  `idClient` int(11) NOT NULL,
  `nom` varchar(255) NOT NULL,
  `prenom` varchar(255) NOT NULL,
  `cin` int(8) NOT NULL,
  `adresse` varchar(255) NOT NULL,
  `telephone` int(8) NOT NULL,
  `email` varchar(255) NOT NULL,
  `motdepasse` varchar(255) NOT NULL,
  `dateCreation` datetime NOT NULL DEFAULT current_timestamp(),
  `dernierLogin` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `location`
--

CREATE TABLE `location` (
  `idLocation` int(11) NOT NULL,
  `idClient` int(11) NOT NULL,
  `idVoiture` int(11) NOT NULL,
  `dateDebut` datetime NOT NULL,
  `dateFin` datetime NOT NULL,
  `coutTotal` decimal(10,3) NOT NULL,
  `estTerminee` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `voiture`
--

CREATE TABLE `voiture` (
  `idVoiture` int(11) NOT NULL,
  `matricule` varchar(10) NOT NULL,
  `marque` varchar(255) NOT NULL,
  `modele` varchar(255) NOT NULL,
  `annee` year(4) NOT NULL,
  `couleur` varchar(255) NOT NULL,
  `prixLocation` decimal(10,3) NOT NULL,
  `disponible` tinyint(1) NOT NULL,
  `image` varchar(255) NOT NULL,
  `dateAjout` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `voiture`
--

INSERT INTO `voiture` (`idVoiture`, `matricule`, `marque`, `modele`, `annee`, `couleur`, `prixLocation`, `disponible`, `image`, `dateAjout`) VALUES
(1, '176TUN2652', 'Audi', 'A1', '2016', 'Blanc', 300.000, 1, 'img/A1.png', '2023-04-28 13:20:00'),
(2, '185TUN4862', 'Audi', 'A3', '2018', 'Bleu', 500.000, 1, 'img/A3.png', '2023-04-28 13:21:00'),
(3, '205TUN1972', 'Audi', 'A6', '2020', 'Gris', 660.000, 1, 'img/A6.png', '2023-04-28 13:22:00');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `administrateur`
--
ALTER TABLE `administrateur`
  ADD PRIMARY KEY (`idAdmin`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `client`
--
ALTER TABLE `client`
  ADD PRIMARY KEY (`idClient`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `cin` (`cin`),
  ADD UNIQUE KEY `telephone` (`telephone`);

--
-- Indexes for table `location`
--
ALTER TABLE `location`
  ADD PRIMARY KEY (`idLocation`),
  ADD KEY `FK_CLIENT` (`idClient`),
  ADD KEY `FK_VOITURE` (`idVoiture`);

--
-- Indexes for table `voiture`
--
ALTER TABLE `voiture`
  ADD PRIMARY KEY (`idVoiture`),
  ADD UNIQUE KEY `matricule` (`matricule`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `administrateur`
--
ALTER TABLE `administrateur`
  MODIFY `idAdmin` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `client`
--
ALTER TABLE `client`
  MODIFY `idClient` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `location`
--
ALTER TABLE `location`
  MODIFY `idLocation` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `voiture`
--
ALTER TABLE `voiture`
  MODIFY `idVoiture` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `location`
--
ALTER TABLE `location`
  ADD CONSTRAINT `FK_CLIENT` FOREIGN KEY (`idClient`) REFERENCES `client` (`idClient`),
  ADD CONSTRAINT `FK_VOITURE` FOREIGN KEY (`idVoiture`) REFERENCES `voiture` (`idVoiture`);

DELIMITER $$
--
-- Events
--
CREATE DEFINER=`root`@`localhost` EVENT `update_location_status` ON SCHEDULE EVERY 1 DAY STARTS '2023-04-28 00:00:00' ON COMPLETION NOT PRESERVE ENABLE DO UPDATE location SET estTerminee = true WHERE dateFin = CURDATE()$$

CREATE DEFINER=`root`@`localhost` EVENT `update_disponible` ON SCHEDULE EVERY 1 DAY STARTS '2023-04-28 00:00:00' ON COMPLETION NOT PRESERVE ENABLE DO UPDATE Voiture v
    INNER JOIN Location l ON v.idVoiture = l.idVoiture
    SET v.disponible = 1
    WHERE l.estTerminee = 1 AND v.disponible = 0 AND l.dateFin = CURDATE()$$

DELIMITER ;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
