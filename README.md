# Projet : 


# Instructions non exhaustives : 

* Installer les **dépendances** : 

    ```bash
    composer install
    ```

* Dupliquer le fichier `.env` et le renommer `.env.local`

    * Mettre vos informations de **connexion** à la base de donnée

    * Créer la BDD :
    
        ```bash
        php bin\console d:d:c
        ```

    * Si il y en a, executez les **migrations** :

        ```bash
        php bin\console d:m:m
        ```

    * Générer les clefs privé & publique **JWT**
        Décommenter extension=sodium dans le php.ini // redémarrer server et éditeur de code

        Installation du bundle Composer
        ```bash
         composer require lexik/jwt-authentication-bundle
        ```

       Clefs
        ```bash
         symfony console lexik:jwt:generate-keypair
        ```
        
        Si la commande ne fonctionne pas, créez le dossier config/jwt à la main et ouvrez un terminal Git Bash :
        ```bash
         openssl genrsa -out config/jwt/private.pem -aes256 4096
        ```
        (mettre à jour le `.env.local` )

        ```bash
        openssl genrsa -out config/jwt/private.pem -aes256 4096
        ```

    * Mettre vos variables d'environnement **Mailtrap**

* Faire tourner Messenger pour les tâches asynchrone comme l'envoie de mail :

    ```bash
    php bin/console messenger:consume async -vv
    ```

<!-- * Les **icones** : https://fontawesome.com/v4/icons/ -->

# TO DO / FINISH

* Mettre tous les **Asserts**
* Verifier les **Flash**