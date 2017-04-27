# Camagru (project of school 42)
#### Objectif
Ce projet propose de créer une petite application web permettant de réaliser des
montages basiques à l’aide d'une webcam et d’images prédéfinies.

Un utilisateur du site devra donc être capable de sélectionner une image dans
une liste d’images superposables (par exemple, des cadres ou des objets à l’utilité douteuse),
prendre une photo depuis sa webcam et admirer le résultat d’un montage digne
de James Cameron.

Toutes les images prises devront être publiques, likeables et commentables.

#### Contraintes
* Framework et et library interdit

# Prérequis

Apache, MySql et PHP

# Installation

Ouvrir le shell et se mettre à la racine du projet.

Pour installer la base de données, il faut configurer le fichier application/config/database.php et lancer le script application/config/setup.php:

php -n application/config/setup.php

## Notes

- MySQL with PDO
- PhpMyAdmin
- Local authentication and connexion system
- Proper data validation for security concern
- Webcam streaming
- Picture capturing with filters/layers added
- Likes
- Comments
- Emailing for account validation and forgotten password
- File uploading
- Responsive design
- MVC design pattern wannabe
- Procedural code
- Firefox and Chrome support

