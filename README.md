# Projet : 


# Instructions non exhaustives : 

## Installer les dépendances & bundles nécessaires

```bash
    composer install
```
(Décommenter extension=sodium dans fichier serveur/bin/php/versionVoulue/php.ini // redémarrer server et éditeur de code)

## Dupliquer le fichier `.env` et le renommer `.env.local` 

* Mettre vos informations de **connexion** à la base de donnée

    Créer la BDD :

 ```bash
    php bin\console d:d:c
```

* Si il y en a, executez les **migrations** :

 ```bash
    php bin\console d:m:m
```
## Générer les clefs privé & publique JWT

* Installation du bundle Composer
```bash
    composer require lexik/jwt-authentication-bundle
```

* Clefs  
```bash
    symfony console lexik:jwt:generate-keypair
```
    
Si la commande ne fonctionne pas, créez le dossier config/jwt à la main et ouvrez un terminal Git Bash :
```bash
    openssl genrsa -out config/jwt/private.pem -aes256 4096
    openssl rsa -pubout -in config/jwt/private.pem -out config/jwt/public.pem
```
(mettre à jour le `.env.local` // passphrase )


## Lancer le serveur
```bash
    symfony server:start
```

## Couper le serveur
```bash
    symfony server:stop
```

## Ajouter vos variables d'environnement **Mailtrap**

Ensuite, faîtes tourner Messenger pour les tâches asynchrone comme l'envoie de mail :

```bash
    php bin/console messenger:consume async -vv
```

<!-- * Les **icones** : https://fontawesome.com/v4/icons/ -->
