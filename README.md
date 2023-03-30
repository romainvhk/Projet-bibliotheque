## Initialiser le projet Symfony
Créer le projet avec la commande : 
```
symfony new --webapp --version=lts nomduprojet
```

## Installation des dépendances (avec composer)
Installation de doctrine/fixtures-bundle :
```
$ composer require orm-fixtures --dev
```

Installation de faker :
```
$ composer require fakerphp/faker --dev
```

Lors d'un git clone, pour installer les dépendances : 
```
composer install
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

## Création de la base de données et configuration
Création d'une BDD nommée projet_bibliothèque avec ls install scripts :
```
$ cd ~/install-scripts
$ ./mkdb.sh src_symfony_5_4
```

Création du fichier de config local
```
cd nomduprojet
touch .env.local
```

Dans le fichier .env.local, ajouter les accès à la BDD
```
APP_ENV=dev
DATABASE_URL="mysql://nomdelabdd:motdepasse@127.0.0.1:3306/nomdelabdd?serverVersion=mariadb-10.8.3&charset=utf8mb4"
```
Attention, à modifier en fonction de votre service de bdd et de sa version, et de l'adresse IP.


## Ajout des tables dans la BDD

Pour créer les tables de la BDD, il faut utiliser la commande : 
```php bin/console make:entity```

Laisser le terminal vous guider pour la création des colonnes. 

Une fois les tables créées, il faut réaliser dans l'ordre et à la suite les commandes suivantes : 
``` php bin/console do:mi:di ```
``` php bin/console do:mi:mi ```

## Création des fixtures

Pour créer le fichier des fixtures, il faut utiliser la commande : 
``` php bin/console make:fixtures ```

## Création du controller

Pour créer le fichier controller, il faut utiliser la commande : 
``` php bin/console make:controller ```

## URL pour tester le controller

Pour tester les "users" : localhost:8000/test/user
Pour tester les "livres" : localhost:8000/test/livre
Pour tester les "emprunteurs" : localhost:8000/test/emprunteur
Pour tester les "emprunts" : localhost:8000/test/emprunt