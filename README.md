## Installation avec docker

- docker-compose build --no-cache
- docker-compose up -d 

Pour Windows : 
```
$ cd docker/nginx/
$ find . -name "*.sh" | xargs dos2unix
```

## Debug docker 

- docker-compose ps
- docker-compose logs -f [CONTAINER(php|node|nginx|db)]

## Commandes utiles

#####Maker
```
docker-compose exec php bin/console make:controller
docker-compose exec php bin/console make:entity
docker-compose exec php bin/console make:form
docker-compose exec php bin/console make:crud
```

## Doctrine
#####Mise à jour de votre BDD
```
docker-compose exec php bin/console doctrine:schema:update --dump-sql
docker-compose exec php bin/console doctrine:schema:update --force
```
#####Relation
https://symfony.com/doc/current/doctrine/associations.html 

#####Custom query avec DQL (repository)
https://symfony.com/doc/current/doctrine.html#querying-with-the-query-builder
https://www.doctrine-project.org/projects/doctrine-orm/en/current/reference/query-builder.html

#####MAJ BDD et Migration
https://symfony.com/doc/current/bundles/DoctrineMigrationsBundle/index.html
```
docker-compose exec php bin/console doctrine:schema:update --dump-sql
docker-compose exec php bin/console doctrine:schema:update --force
docker-compose exec php bin/console make:migration
```

##Gestion des Fixtures
https://symfony.com/doc/current/bundles/DoctrineFixturesBundle/index.html 
```
composer require --dev doctrine/doctrine-fixtures-bundle
docker-compose exec php bin/console make:fixtures
php bin/console doctrine:fixtures:load
```

##Creation d'auth 
``` 
docker-compose exec php bin/console make:user 
// changer au sein de l'entity user les règles de votre table 
@ORM\Table(name="user_account", schema="PROJECT_NAME") 

docker-compose exec php bin/console make:auth 
docker-compose exec php bin/console security:encode-password 
```
