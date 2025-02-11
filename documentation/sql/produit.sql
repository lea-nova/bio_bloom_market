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
-- Structure de la table `produit`
--

CREATE TABLE `produit` (
  `id` int(11) NOT NULL,
  `marque_id` int(11) DEFAULT NULL,
  `fournisseur_id` int(11) DEFAULT NULL,
  `nom` varchar(255) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `slug` varchar(255) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `visible` tinyint(1) NOT NULL,
  `stock` int(11) NOT NULL,
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `updated_at` datetime DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `produit`
--

INSERT INTO `produit` (`id`, `marque_id`, `fournisseur_id`, `nom`, `description`, `slug`, `image`, `visible`, `stock`, `created_at`, `updated_at`) VALUES
(15, 28, 2, 'Lait d’Amande Naturel', NULL, 'lait-d-amande-naturel', 'lait-d-amande-bio-67863a28db99b.jpg', 1, 0, '2025-01-10 18:24:50', '2025-01-23 14:40:45'),
(21, 27, NULL, 'Pommes Gala', 'Des pommes sucrées et juteuses, cultivées sans pesticides pour préserver leur goût authentique.', 'pommes-gala-bio', 'pommes-gala-67863a117e170.jpg', 1, 0, '2025-01-13 00:59:41', '2025-01-14 21:07:48'),
(22, 27, NULL, 'Carottes des Sable', 'Des carottes riches en saveurs, parfaites pour les soupes, purées et plats mijotés.', 'carottes-des-sables-bio', 'carottes-bio-67863a0048fb7.jpg', 1, 0, '2025-01-13 01:02:17', '2025-01-14 21:07:58'),
(23, 27, NULL, 'Oranges Navel Bio', 'Oranges vitaminées, idéales pour les jus frais ou comme collation.', 'oranges-navel-bio', 'oranges-678639a59fff3.jpg', 1, 0, '2025-01-13 14:07:28', '2025-02-03 23:40:54'),
(24, 27, 1, 'Courgettes Bio', 'Courgettes tendres et savoureuses, idéales pour les ratatouilles et gratins.', 'courgettes-bio', 'courgette-67863997c7ca8.jpg', 0, 0, '2025-01-13 14:11:26', '2025-01-14 11:16:55'),
(25, 27, 1, 'Pommes de Terre Bio', 'Pommes de terre fermes et polyvalentes, parfaites pour les frites ou purées.', 'pommes-de-terre-bio', 'pomme-de-terre-6786393718553.jpg', 0, 0, '2025-01-13 14:12:31', '2025-01-14 21:07:26'),
(26, 27, NULL, 'Salade Laitue Bio', 'Laitue croquante et fraîche, parfaite pour vos salades estivales.', 'salade-laitue-bio', 'salade-6786398b285fb.jpg', 0, 0, '2025-01-13 14:13:15', '2025-01-14 21:07:32');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `produit`
--
ALTER TABLE `produit`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_29A5EC274827B9B2` (`marque_id`),
  ADD KEY `IDX_29A5EC27670C757F` (`fournisseur_id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `produit`
--
ALTER TABLE `produit`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `produit`
--
ALTER TABLE `produit`
  ADD CONSTRAINT `FK_29A5EC274827B9B2` FOREIGN KEY (`marque_id`) REFERENCES `marque` (`id`),
  ADD CONSTRAINT `FK_29A5EC27670C757F` FOREIGN KEY (`fournisseur_id`) REFERENCES `fournisseur` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
