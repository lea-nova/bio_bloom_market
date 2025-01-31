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

INSERT INTO `fournisseur` (`id`, `nom`, `telephone`, `mail`, `service`) VALUES
(1, 'Nature & Co', '0256789012', 'contact@natureco.fr', 'Fruits et légumes bio'),
(2, 'BioPrime', '6703890123', 'service@bioprime.com', 'Produits laitiers bio');

INSERT INTO `marque` (`id`, `nom`, `slug`, `description`, `logo`, `created_at`, `updated_at`, `active`) VALUES
(27, 'Terre Bio', 'terre-bio', 'Une marque spécialisée dans les fruits et légumes bio de saison, cultivés dans le respect des travailleurs et de l\'environnement.', 'terre-bio-logo-67863a4e5d814.jpg', '2025-01-09 23:55:52', '2025-01-14 17:50:49', 0),
(28, 'Green Morning', 'green-morning', 'Produits pour le petit-déjeuner : céréales, barres énergétiques et mueslis bio riches en nutriments.', 'logo-bbm-6-67863a5f89d8a.jpg', '2025-01-09 23:56:16', '2025-01-14 17:47:40', 1);


INSERT INTO `produit` (`id`, `marque_id`, `fournisseur_id`, `nom`, `description`, `slug`, `image`, `visible`, `stock`, `created_at`, `updated_at`) VALUES
(15, 28, 2, 'Lait d’Amande Naturel', NULL, 'lait-d-amande-naturel', 'lait-d-amande-bio-67863a28db99b.jpg', 1, 0, '2025-01-10 18:24:50', '2025-01-23 14:40:45'),
(21, 27, NULL, 'Pommes Gala', 'Des pommes sucrées et juteuses, cultivées sans pesticides pour préserver leur goût authentique.', 'pommes-gala-bio', 'pommes-gala-67863a117e170.jpg', 1, 0, '2025-01-13 00:59:41', '2025-01-14 21:07:48'),
(22, 27, NULL, 'Carottes des Sable', 'Des carottes riches en saveurs, parfaites pour les soupes, purées et plats mijotés.', 'carottes-des-sables-bio', 'carottes-bio-67863a0048fb7.jpg', 1, 0, '2025-01-13 01:02:17', '2025-01-14 21:07:58'),
(23, 27, NULL, 'Oranges Navel Bio', 'Oranges vitaminées, idéales pour les jus frais ou comme collation.', 'oranges-navel-bio', 'oranges-678639a59fff3.jpg', 0, 0, '2025-01-13 14:07:28', '2025-01-14 21:07:19'),
(24, 27, 1, 'Courgettes Bio', 'Courgettes tendres et savoureuses, idéales pour les ratatouilles et gratins.', 'courgettes-bio', 'courgette-67863997c7ca8.jpg', 0, 0, '2025-01-13 14:11:26', '2025-01-14 11:16:55'),
(25, 27, 1, 'Pommes de Terre Bio', 'Pommes de terre fermes et polyvalentes, parfaites pour les frites ou purées.', 'pommes-de-terre-bio', 'pomme-de-terre-6786393718553.jpg', 0, 0, '2025-01-13 14:12:31', '2025-01-14 21:07:26'),
(26, 27, NULL, 'Salade Laitue Bio', 'Laitue croquante et fraîche, parfaite pour vos salades estivales.', 'salade-laitue-bio', 'salade-6786398b285fb.jpg', 0, 0, '2025-01-13 14:13:15', '2025-01-14 21:07:32');


INSERT INTO `produit_categorie` (`produit_id`, `categorie_id`) VALUES
(15, 2),
(21, 1),
(22, 1),
(23, 1),
(24, 1),
(25, 1),
(26, 1);