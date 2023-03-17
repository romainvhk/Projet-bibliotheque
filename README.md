## Installation des dépendances (avec composer)
Installation de doctrine/fixtures-bundle :
```
$ composer require orm-fixtures --dev
```

Installation de faker :
```
$ composer require fakerphp/faker --dev
```
## Création du fichier dofilo.sh
Créer le fichier bin/dofilo.sh
```
#!/bin/bash

php bin/console doctrine:database:drop --force --if-exists
php bin/console doctrine:database:create --no-interaction
php bin/console doctrine:migrations:migrate --no-interaction
php bin/console doctrine:fixtures:load --no-interaction
```
Ce fichier permet de détruire la BDD, de la recréer, d'importer le fichier de migration dans la BDD et de charger les fixtures.

## Création de la base de données
Création d'une BDD nommée projet_bibliothèque avec ls install scripts :
```
$ cd ~/install-scripts
$ ./mkdb.sh src_symfony_5_4
```

## Ajout des tables dans la BDD

Pour créer les tables de la BDD, il faut utiliser la commande : 
```
php bin/console make:entity
```
Laisser le terminal vous guider pour la création des colonnes. 

Une fois les tables créées, il faut réaliser dans l'ordre et à la suite les commandes suivantes : 
```
php bin/console do:mi:di
```
```
php bin/console do:mi:mi
```
