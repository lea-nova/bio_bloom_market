-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : mar. 11 fév. 2025 à 17:34
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
-- Structure de la table `categorie`
--

CREATE TABLE `categorie` (
  `id` int(11) NOT NULL,
  `nom` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` longtext DEFAULT NULL,
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `updated_at` datetime DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)',
  `visible` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `categorie`
--

INSERT INTO `categorie` (`id`, `nom`, `slug`, `description`, `created_at`, `updated_at`, `visible`) VALUES
(1, 'Fruits et légumes', 'fruits-et-legumes', 'Une sélection de fruits et légumes 100 % biologiques, frais et de saison, cultivés localement.', '2025-01-08 01:32:44', '2025-01-14 18:01:37', 1),
(2, 'Produits laitiers', 'produits-laitiers', 'Fromages, yaourts, et pleins d\'autres produits laitiers issus de fermes biologiques respectueuses.', '2025-01-08 01:38:52', '2025-01-14 18:02:17', 1),
(3, 'Épicerie sucrée', 'epicerie-sucree', 'Biscuits, confitures, et douceurs sucrées préparés avec des ingrédients bio de qualité.', '2025-01-08 01:41:39', '2025-01-14 18:02:25', 1),
(4, 'Épicerie salée', 'epicerie-salee', 'Riz, pâtes, conserves, et autres produits d’épicerie pour vos recettes bio.', '2025-01-08 01:42:00', '2025-01-14 18:02:29', 1),
(5, 'Boissons bio', 'boissons-bio', 'Jus de fruits, thés, cafés et boissons sans alcool bio pour tous les goûts.', '2025-01-08 01:42:16', '2025-01-14 18:02:33', 1),
(6, 'Produits sans gluten', 'produits-sans-gluten', 'Une gamme variée de produits bio spécialement conçus pour les régimes sans gluten.', '2025-01-08 01:42:27', '2025-01-14 18:02:36', 1),
(7, 'Alternatives végétariennes', 'alternatives-vegetariennes', 'Protéines végétales, tofu, tempeh et autres alternatives bio pour une alimentation équilibrée.', '2025-01-08 01:42:38', NULL, 1),
(8, 'Plantes aromatiques et herbes', 'plantes-aromatiques-et-herbes', 'Tisanes, infusions, et épices bio pour rehausser vos plats ou vos moments de détente.', '2025-01-08 01:42:49', NULL, 1),
(9, 'Produits d’entretien écologique', 'produits-d-entretien-ecologique', 'Lessives, nettoyants et autres produits respectueux de l’environnement pour votre maison.', '2025-01-08 01:43:02', '2025-01-16 16:04:25', 0),
(10, 'Snacks et encas bio', 'snacks-et-encas-bio', 'Barres énergétiques, fruits secs, et collations saines pour les petites faims.', '2025-01-08 01:43:12', NULL, 1),
(11, 'Produits en vrac', 'produits-en-vrac', 'Riz, légumineuses, fruits secs et autres produits bio disponibles en vrac pour limiter les déchets.', '2025-01-08 01:54:43', NULL, 1);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `categorie`
--
ALTER TABLE `categorie`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_497DD634989D9B62` (`slug`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `categorie`
--
ALTER TABLE `categorie`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
