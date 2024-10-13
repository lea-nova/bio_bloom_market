## [2024-10-12]

### Résolutions
- Correction du bug d'ID auto-incrémenté.
- Implémentation des UUID dans l'entité `User`.
- Mise à jour des URLs pour utiliser les UUID.
- Comparaison des UUID en chaînes de caractères.
- Fixtures pour l'entité ``User`` et test 
---

### Détails des Changements

#### 1. Correction du Bug d'ID Auto-Incrémenté
- Définition correcte de la colonne `id` comme `integer` et auto-incrémenté.
- Application des migrations nécessaires.

#### 2. Génération d'UUID
- Ajout du champ `uuid` dans l'entité `User`.
- Utilisation de `Uuid::uuid4()` pour la génération d'UUID.

#### 3. Mise à Jour des URLs
- Modification des routes pour utiliser les UUID.
- Mise à jour des contrôleurs et des templates pour refléter ce changement.

#### 4. Comparaison des UUIDs
- Conversion des UUIDs en chaînes de caractères avant comparaison pour garantir l'accès sécurisé.

#### 5. Création fixtures pour l'entité ``User``

## [2024-10-13]
### Ajouts 
- Ajout de colonnes dans la table `Adresse`, *** ulid, createdAt, updatedAt, deletedAt ***
- Date par défaut [13/10/2024] pour les enregistrements déjà existants. 

