# CoPath - API (Back-End)

Projet de fin de formation // Certification développeur web  
**Stack :** Symfony (PHP), Doctrine ORM, JWT Auth

---

## Présentation

Ce dépôt contient l’API back-end de CoPath, permettant la gestion des utilisateurs, scénarios et campagnes, et offrant des endpoints sécurisés pour l’application front-end.

---

## Prérequis

- PHP >= 8.1
- Composer
- Extension sodium activée
- Serveur Symfony CLI ou Apache/Nginx
- Base de données MySQL/MariaDB

---

## Installation

### 1. Installer les dépendances

```bash
composer install
```
> **Astuce :** Activez (décommentez) l’extension sodium dans votre fichier `php.ini` si nécessaire. Ici : serveur/bin/php/versionVoulue/php.ini. Pensez à redémarrer serveur et IDE.

### 2. Configuration de l’environnement

- Dupliquez le fichier `.env` en `.env.local`
- Renseignez vos informations de connexion à la base de données

Créer la base :

```bash
php bin/console doctrine:database:create
```

Lancer les migrations :

```bash
php bin/console doctrine:migrations:migrate
```

(Lancer les fixtures - facultatif)

```bash
php bin/console doctrine:fixtures:load
```

### 3. Générer les clefs privée et publique JWT


```bash
composer require lexik/jwt-authentication-bundle
symfony console lexik:jwt:generate-keypair
```
> Si besoin, créez manuellement `config/jwt` et utilisez, dans un terminal **Git Bash**, openssl :

```bash
openssl genrsa -out config/jwt/private.pem -aes256 4096
openssl rsa -pubout -in config/jwt/private.pem -out config/jwt/public.pem
```
N’oubliez pas d’actualiser la passphrase dans `.env.local`.

### 4. Lancer le serveur

```bash
symfony server:start
```

Arrêt :

```bash
symfony server:stop
```

### 5. Configuration Mailtrap (emails de dev)

- Ajoutez vos variables d’environnement Mailtrap dans `.env.local`.
- Démarrez Messenger pour les envois asynchrones :

```bash
php bin/console messenger:consume async -vv
```

---

## Endpoints principaux (exemples)

- `/api/login_check` — Authentification (JWT)
- `/api/users` — Gestion des utilisateurs
- `/api/scenarios` — Gestion des scénarios
- `/api/campaigns` — Gestion des campagnes

> Voir la documentation de l’API ou le code source pour la liste complète.

---

## Administration

- Dashboard EasyAdmin accessible pour les administrateurs

---

## Bonnes pratiques

- Respectez la structure du projet Symfony
- Sécurisez les routes sensibles
- Testez les endpoints avec Postman ou Insomnia

---

## Contribution

Toute contribution est la bienvenue !  
Merci d’ouvrir une issue ou une pull request si besoin.

---

## Contact

Pour tout souci ou question, contactez : [Esteranodin](https://github.com/Esteranodin)
