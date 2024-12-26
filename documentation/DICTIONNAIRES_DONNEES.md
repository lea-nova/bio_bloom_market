# Dictionnaire des données

## Entités

#### User

| Attribut        | Type       | Description                           |
| --------------- | ---------- | ------------------------------------- |
| id PK           | integer    | Id unique auto incrémenté             |
| email           | string     | Email utilisateur                     |
| roles           | string     | Roles attribués                       |
| password        | string     | Mot de passe hashé                    |
| nom             | string     | Nom utilisateur                       |
| prenom          | string     | Prénom utilisateur                    |
| telephone       | string(10) | Numéro téléphone utilisateur          |
| date_naissance  | date       | Date de naissance utilisateur         |
| fidelite_client | boolean    | Client a la carte fidélité ou non     |
| pref_achat      | string     | Préférence achat de l'utilisateur     |
| uuid            | binary(16) | UUID à afficher dans l'URL            |
| created_at      | datetime   | Timestamps création du compte         |
| updated_at      | datetime   | Timestamps mise a jour info du compte |
| deleted_at      | datetime   | Timestamps suppression du compte      |

#### Adresse

| Attribut    | Type      | Description                           |
| ----------- | --------- | ------------------------------------- |
| id PK       | integer   | Id unique auto incrémenté             |
| ligne1      | string    | ligne 1 adresse                       |
| ligne2      | string    | ligne 2 adresse (optionnel)           |
| code_postal | string(5) | code postal FR                        |
| ville       | string    | ville associée au code postal         |
| pays        | string    | Pays associé à la ville               |
| ulid        | binary    | ULID pour l'affichage dans l'URL      |
| created_at  | datetime  | Timestamps création du compte         |
| updated_at  | datetime  | Timestamps mise a jour info du compte |
| deleted_at  | datetime  | Timestamps suppression du compte      |

#### Fournisseur

| Attribut  | Type       | Description                      |
| --------- | ---------- | -------------------------------- |
| id PK     | integer    | Id unique auto incrémenté        |
| nom       | string     | Nom de l'entreprise              |
| telephone | string(10) | téléphone                        |
| mail      | string     | email de la personne à contacter |
| service   | string     | service du Fournisseur           |

### FournisseurAdresse

| Attribut          | Type     | Description                                                       |
| ----------------- | -------- | ----------------------------------------------------------------- |
| id PK             | integer  | Id unique auto incrémenté                                         |
| fournisseur_id FK | integer  | Clé étrangère de Fournisseur                                      |
| adresse_id FK     | integer  | Clé étrangère d'Adresse                                           |
| created_at        | datetime | Timestamps création du compte                                     |
| updated_at        | datetime | Timestamps mise a jour info du compte                             |
| isApproved        | bool     | Permet de vérifier si l'adresse est \n approuvée par l'entreprise |

### UserAdresse

| Attribut      | Type     | Description                                                                       |
| ------------- | -------- | --------------------------------------------------------------------------------- |
| id PK         | integer  | Id unique auto incrémenté                                                         |
| user_id FK    | integer  | Clé étrangère de User                                                             |
| adresse_id FK | integer  | Clé étrangère d'Adresse                                                           |
| created_at    | datetime | Timestamps création du compte                                                     |
| updated_at    | datetime | Timestamps mise a jour info du compte                                             |
| isDefault     | bool     | Permet de vérifier si l'adresse est par \n défaut dans le profil de l'utilisateur |
