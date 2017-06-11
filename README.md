# Projet X

## 1. Environnement technique
* [Vagrant](https://www.vagrantup.com/) : pour déployer facilement une machine virtuelle préconfigurée
* [Symfony 3](https://symfony.com/) : Met à disposition une API REST + affiche la page d'accueil du site 
* [AngularJS](https://angularjs.org/) : S'occupe de la mise en page des informations reçues via l'API REST

## 2. Composants utilisés

### 2.1 Front
* [Bootstrap 3](http://getbootstrap.com/) : Bibliothèque de styles CSS
* [FontAwesome](http://fontawesome.io/) : Bibliothèque d'icon au format Font

### 2.2 Back
* [FOSJsRoutingBundle](https://github.com/FriendsOfSymfony/FOSJsRoutingBundle) : Génère un fichier JS contenant toutes 
les routes de l'API REST 
* [FOSUserBundle](https://github.com/FriendsOfSymfony/FOSUserBundle) : Gère tout ce qui est lié au compte utilisateur :
    * Création de compte
    * Connexion / déconnexion
* [DunglasAngularCsrfBundle](https://github.com/dunglas/DunglasAngularCsrfBundle) : Génère un token Csrf pour AngularJS

## 3. Prérequis
* [Vagrant](https://www.vagrantup.com/downloads.html)
* [VirtualBox](https://www.vagrantup.com/downloads.html)

## 4. Mise en route du projet
* Il faut télécharger la box vagrant et l'installer sous le nom : `debian-jessie`
* Ensuite, à la racine du projet : `$~:vagrant up`
* _N'est plus utile_ : <s>Une fois l'installation terminée : `$~:vagrant ssh`, on se retrouve connecté en SSH à la box
Vagrant</s>
* _N'est plus utile_ : <s>Dans la box :
    * `$~: php composer.phar upade` : installation des vendor PHP par Composer
    * `$~: bower update` : installation des composants front par Bower
    * `$~: npm update --no-bin-links` : installation des composants NodeJS utiles pour Grunt
    * `$~: grunt default` : execution des tâches Grunt de base</s>
    
* Aller ensuite sur [`192.168.33.10/app_dev.php/`](192.168.33.10/app_dev.php/) pour accéder à la page d'accueil du site
