#!/usr/bin/env bash

PROJECT_FOLDER='/home/vagrant/src'
SH_FILE_PATH="$PROJECT_FOLDER/vagrant-provisioning/3-create-databases/script.sh"
RESULT_FILE_PATH=${SH_FILE_PATH/.sh/.result}
if [ -f "$RESULT_FILE_PATH" ]
then
    echo "$SH_FILE_PATH a deja ete execute. Voir le rapport dans $RESULT_FILE_PATH"
	exit
fi

# Base de tests unitaires
cp /vagrant-provisioning/3-create-databases/parameters.yml.unit-test app/config/parameters.yml
php bin/console doctrine:database:create
php bin/console doctrine:schema:update --force

# Base de développement (on laisse la config en place ensuite)
cp /vagrant-provisioning/3-create-databases/parameters.yml.dev app/config/parameters.yml
php bin/console doctrine:database:create
php bin/console doctrine:schema:update --force

# Ecriture du fichier de résultat
touch "$RESULT_FILE_PATH"
echo "Base de donnée de dev \"projet_x\" créée et schéma initialisé" >> "$RESULT_FILE_PATH"
echo "Base de donnée de test unitaires \"unit_test_projet_x\" créée et schéma initialisé" >> "$RESULT_FILE_PATH"
