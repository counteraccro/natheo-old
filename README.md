# Natheo CMS

NathéoCMS est un petit CMS développé en PHP et qui permet de faire une gestion de contenu simple multi langue.

Fonctionnalités
- 

/!\ *Le CMS étant en développement la liste des fonctionnalités peut évoluer à tout moment et n'est pas forcément à jour*

Liste des fonctionnalités présente sur le CMS :
- **Gestion des thèmes**
  - Switcher entre les deux thèmes présents par défauts ou développer votre propre thème.
- **Pages**
  - Créer vos propres contenus via une interface simple dans les 5 langues du site.
  - Avec les 3 modèles de structure de page, positionner de façon précise vos modules / textes / médias. 
- **Médiathèque**
  - Ajouter des images / vidéos / documents / sons via une interface simple.
  - Ranger vos médias dans des dossiers personnalisés.
- **Gestion des utilisateurs**
  - Créer / éditer / supprimer les utilisateurs qui auront accès à l'administration.
- **Gestion des rôles**
  - Créer / éditer / supprimer vos propres rôles, gérer de façon fine les accès à chaque fonctionnalité de l'administration.
- **Gestion des traductions**
  - Le site est actuellement traduit en 5 langues (Français, Anglais, Italien, Allemand, Espagnole).
  - Créer votre contenu dans les 5 langues du site.
  - Editer de façon dynamique les traductions statiques du CMS.
- **Gestion des routes**
  - Permet de gérer les routes internes du CMS (pour le développement).
- **Options globales**
  - Les options standards d'un CMS.
- **Modules**
  - Liste des modules disponible actuellement pour le CMS :
    - **FAQ**
      - Gestion d'une FAQ dans les 5 langues du site, définissez vos catégories et questions / réponses associées.
    - **TAG**
      - Gestion des tags utilisé pour vos contenus.


Informations
-
Technologies :
 - PHP 8.1
 - Symfony 6.0.0
 - Jquery 3.6.0
 - Bootstrap 5.1.3
 
Autres :
 - summernote 0.8.20
 - NatheoCMS [en cours de dev]

Installation
-

Étape 1 : cloner le dépôt GIT

`https://github.com/counteraccro/natheo.git`

Étape 2 : Installer Symfony

`composer update` ou via https://symfony.com/download

Étape 3 : installer la base de données

`symfony console doctrine:database:create`

Étape 4 : récupération des tables de la base de données

`symfony console doctrine:schema:update --force`

Étape 5: installation des fixtures

`symfony console doctrine:fixture:load`

Étape 6: accès au projet via l'url

`http://localhost:8000`

Communauté
-

Envi de nous aider pour :
- Le développement du CMS
- La traduction
- La découverte de bugs
- Ou tout simplement venir discuter du projet / poser vos questions

Discord officiel : https://discord.gg/vMY2RNZsKn