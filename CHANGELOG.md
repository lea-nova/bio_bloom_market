# Changelog

Toutes les modifications notables lors des itérations seront documentées ici.

## [1.0.0] - 2024-12-26

### Features développées

- Inscription
  - Création de compte utilisateur
  - Modification mot de passe ( vieux mot de passe + deux fois nouveau )
- Connexion/Déconnexion
  - Authentification sécurisée
  - G
- Utilisateur
  - CRUD :
    - **Création** : Enregistrement d’un nouvel utilisateur
    - **Lecture** : Affichage des informations utilisateurs afficher informations utilisateurs, modifications des coordonnées )
    - **Mise à jour** : Modification des coordonnées utilisateurs
    - **Suppression** : Suppression d’un utilisateur
    - **Adresse par Utilisateur**
  - Gestion des rôles : ROLE_USER par défaut quand on est connecté.
- Adresse
  - CRUD :
    - **Création** : Ajout d’une nouvelle adresse ( reliée soit à un enregistrement de `User` soit de `Fournisseur`
    - **Lecture** : Affichage des adresses existantes.
    - **Mise à jour** : Modifications des adresses
    - **Suppression** : Suppression d’une adresse.
  - Affichage d’une adresse par défaut :
    - Définition et affichage d’une adresse préférée
- Fournisseur
  - CRUD :
    - **Création** : Ajout d’un nouveau fournisseur
    - **Lecture** : Affichage des informations fournisseur
    - **Mise à jour** : Modification des informations fournisseur
    - **Suppression**: Suppression d’un fournisseur
  - Affichage dans le backoffice : Accessible uniquement aux utiliateurs avec le rôle `ROLE_ADMIN`
- Création des tables `FournisseurAdresse` et `UtilisateurAdresse`
  - Gestion du carnet d'adresses de chaque utilisateur
  - Gestion du carnet d'adresses de chaque fournisseur
