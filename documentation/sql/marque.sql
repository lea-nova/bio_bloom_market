-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : mar. 11 fév. 2025 à 17:35
-- Version du serveur : 10.4.32-MariaDB
-- Version de PHP : 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `bio_bloom_market`
--

-- --------------------------------------------------------

--
-- Structure de la table `marque`
--

CREATE TABLE `marque` (
  `id` int(11) NOT NULL,
  `nom` varchar(255) NOT NULL,
  `slug` varchar(255) DEFAULT NULL,
  `description` longtext DEFAULT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `updated_at` datetime DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)',
  `active` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `marque`
--

INSERT INTO `marque` (`id`, `nom`, `slug`, `description`, `logo`, `created_at`, `updated_at`, `active`) VALUES
(27, 'Terre Bio', 'terre-bio', 'Une marque spécialisée dans les fruits et légumes bio de saison, cultivés dans le respect des travailleurs et de l\'environnement.', 'terre-bio-logo-67863a4e5d814.jpg', '2025-01-09 23:55:52', '2025-01-14 17:50:49', 0),
(28, 'Green Mornings', 'green-mornings', 'Produits pour le petit-déjeuner : céréales, barres énergétiques et mueslis bio riches en nutriments.', 'logo-bbm-6-67863a5f89d8a.jpg', '2025-01-09 23:56:16', '2025-02-03 23:38:29', 1),
(37, 'SaveurVégétale', 'saveurvegetale', 'Alternatives végétariennes et véganes, comme des steaks de soja, tempeh et burgers bio gourmands.', 'logo-bbm-67a145d374750.jpg', '2025-02-03 23:39:23', '2025-02-09 23:24:04', 1);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `marque`
--
ALTER TABLE `marque`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_5A6F91CE6C6E55B5` (`nom`),
  ADD UNIQUE KEY `UNIQ_5A6F91CE989D9B62` (`slug`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `marque`
--
ALTER TABLE `marque`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
