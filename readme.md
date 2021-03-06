# Minecraft items manager
[![N|Solid](http://image.noelshack.com/fichiers/2017/09/1488649702-minecraft-items.gif)](https://www.simonlucas.fr/web/item_manager/)

Minecraft items manager est un site internet permettant à ses utilisateurs de gérer les différents items du jeu et d'y ajouter les leurs. Ce projet répond à un devoir scolaire à [HETIC](https://hetic.net/) consistant a réaliser un gestionnaire d'inventaire PHP sur le thème de notre choix.

![Screenshoot](http://image.noelshack.com/fichiers/2017/10/1489333908-b4d60c2.png)

Ce projet utilise PHP, les bases de données SQL, Javascript, HTML, CSS, Bootstrap & Gulp.

## Installation

- Importer les tables SQL `gestionnaire.sql` via phpmyadmin dans une base de donnée d'interclassement utf8_general_ci
- Modifier le nom de la base de donnée, le nom d'utilisateur et le mot de passe d'accès dans le fichier `src\includes\config.php`
- Executer la commande `npm install` puis `gulp` dans le terminal à la racine
- Lancer `dist\` à sa racine sur un serveur

## Remarques
* Le site internet est directement consultable en ligne à [cette adresse](https://www.simonlucas.fr/web/item_manager/).

Les identifiants de connexion administrateur du site sont les suivants :
- Pseudonyme : `admin`
- Mot de passe : `admin`

## Fonctionnalités

##### Fonctionnalités de base :
* PHP : Lister les items contenus dans la base de donnée
* PHP : Formulaire permettant d'ajouter des items
* PHP : Données associées à chaque item : Nom, ID Minecraft, Image, Catégorie, Description
* PHP : Mise en ligne sécurisée via PDO.

##### Fonctionnalités avancées :
* PHP : Connexion & déconnexion session administrateur avec droits supplémentaires
* PHP : Suppression des items de la base de donnée depuis le site internet (en tant qu'administrateur)
* PHP : Mise en sécurisée d'images sur le serveur (apparence de l'item Minecraft) :
    * Vérification de la taille (15 Ko)
    * Vérification de l'extension
    * Vérification du ratio de l'image (les items sont carrés)
    * Mise en ligne depuis le dossier temporaire du serveur vers le dossier `uploaded\`
* PHP : Vérification de toutes les erreurs de l'utilisateur entrées dans le formulaire :
    * ID Minecraft déjà renseigné dans la base de donnée
    * Donnée manquante dans un input
    * Nombre de caractères minimum / maximum
    * La donnée n'est pas un nombre / est négative
* PHP : Affichage précis des erreurs à l'utilisateur
* PHP : Conservation des données du formulaire en cas d'erreur
* PHP : Formatage en français de la date renvoyée par la base de donnée
* PHP : Conversion de la base de donnée en Json pour une utilisation JS
* PHP : Triage des items selon le choix de l'utilisateur
* JS : Recherche dynamique des items selon leur nom, id & catégorie
* CSS : Site entierement responsive, peu importe le nombre d'items
* CSS : Style CSS reprenant les codes graphiques et audios du jeu Minecraft
* Divers : Utilisation de gulp afin d'optimiser les performances
* Divers : Mise en ligne du site internet et configuration d'apache et de ses droits d'écriture chown pour le stockage des images