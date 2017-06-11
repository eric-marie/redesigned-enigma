#!/usr/bin/env bash

PROJECT_FOLDER='/home/vagrant/src'
SH_FILE_PATH="$PROJECT_FOLDER/vagrant-provisioning/0-always.sh"
RESULT_FILE_PATH=${SH_FILE_PATH/.sh/.result}

# Update / Upgrade
touch "$RESULT_FILE_PATH"
echo "Sauvegarde du resultat de l'apt-get update/upgrade dans : $RESULT_FILE_PATH"
sudo apt-get update > "$RESULT_FILE_PATH"
sudo apt-get -y upgrade >> "$RESULT_FILE_PATH"

# Mise à jour des composants externes
php composer.phar update
bower update
npm update --no-bin-links

# Mise à jour du fichier JS de routes Symfony exposées
php bin/console fos:js-routing:dump

# Execution des tâches Grunt de base
grunt default