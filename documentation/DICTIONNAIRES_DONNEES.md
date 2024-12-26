# Dictionnaire des données

## Entités et Attributs

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

#### Fournisseur

## Relations

### UserAdresse

### AdresseFournisseur
