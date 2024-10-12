# Documentation des Tests

## Création d'Utilisateur
### Objectif
Vérifier que la création d'un utilisateur fonctionne correctement, y compris la génération des UUIDs, les dates, et la persistance dans la base de données.

### Méthodologie
- Créer une instance de l'entité `User`.
- Définir les propriétés de l'utilisateur (UUID, dates, etc.).
- Persister l'utilisateur et vérifier qu'il est correctement enregistré.
- Utilisation de Fixture pour l'entité `User` dans une DB test. 

