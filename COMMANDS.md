# Commandes pour la base de données de test

## Créer et mettre à jour la base de données de test
### Synchroniser la base de données de test avec le schéma. 

**ATTENTION** : Exécuter les commande avec `--env=test`
**1.Mettre à jour la base de données de test :**
```powershell
php bin/console doctrine:database:create --env=test
php bin/console doctrine:schema:update --force --env=test
```

**2. Appliquer les migrations à la base de données de test:**
```powershell
php bin/console doctrine:migrations:migrate --env=test
```

**3. Recharger les fixtures dans la base de données de test (Si besoin de fixtures)**

```powershell
php bin/console doctrine:fixtures:load --env=test
```
