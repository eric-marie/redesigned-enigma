#!/usr/bin/env bash

PROJECT_FOLDER='/home/vagrant/src'
SH_FILE_PATH="$PROJECT_FOLDER/vagrant-provisioning/1-installation-serveur.sh"
RESULT_FILE_PATH=${SH_FILE_PATH/.sh/.result}
if [ -f "$RESULT_FILE_PATH" ]
then
    echo "$SH_FILE_PATH a deja ete execute. Voir le rapport dans $RESULT_FILE_PATH"
	exit
fi

# Simple quotes au lieu de doubles pour ne pas poser de problème avec les caractères spéciaux dans le mot de passe
PASSWORD='projet-x-PWD0'

# Installation Apache et PHP
sudo apt-get install -y apache2
sudo apt-get install -y php5

# Installation MariaDB
sudo debconf-set-selections <<< "mariadb-server-10.0 mysql-server/root_password password $PASSWORD"
sudo debconf-set-selections <<< "mariadb-server-10.0 mysql-server/root_password_again password $PASSWORD"
sudo apt-get -y install mariadb-server mariadb-client
sudo apt-get install php5-mysql

# Installation PHPMyAdmin : même password que pour MariaDB
sudo debconf-set-selections <<< "phpmyadmin phpmyadmin/dbconfig-install boolean true"
sudo debconf-set-selections <<< "phpmyadmin phpmyadmin/app-password-confirm password $PASSWORD"
sudo debconf-set-selections <<< "phpmyadmin phpmyadmin/mysql/admin-pass password $PASSWORD"
sudo debconf-set-selections <<< "phpmyadmin phpmyadmin/mysql/app-pass password $PASSWORD"
sudo debconf-set-selections <<< "phpmyadmin phpmyadmin/reconfigure-webserver multiselect apache2"
sudo apt-get -y install phpmyadmin

# Configuration du fichier hosts
VHOST=$(cat <<EOF
<VirtualHost *:80>
    DocumentRoot "${PROJECT_FOLDER}/web"
    <Directory "${PROJECT_FOLDER}/web">
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
EOF
)
echo "${VHOST}" > /etc/apache2/sites-available/000-default.conf
sudo a2enmod rewrite

# Ajout d'extensions à PHP
sudo apt-get install -y php5-intl php5-xdebug
# Configuration du fuseau horraire du serveur
sudo sed -i -- "s/;date.timezone =/date.timezone = \"Europe\/Paris\"/g" /etc/php5/apache2/php.ini
# Configuration de xdebug
echo "" >> /etc/php5/apache2/php.ini
echo "[xdebug]" >> /etc/php5/apache2/php.ini
echo "zend_extension=xdebug_module_goes_here" >> /etc/php5/apache2/php.ini
echo "xdebug.remote_enable=1" >> /etc/php5/apache2/php.ini
echo "xdebug.remote_host=192.168.33.1" >> /etc/php5/apache2/php.ini
echo "xdebug.remote_port=9000" >> /etc/php5/apache2/php.ini
echo "xdebug.remote_autostart=1" >> /etc/php5/apache2/php.ini

# Redémarrage de Apache
sudo /etc/init.d/apache2 restart

# Installation de Git
sudo apt-get -y install git

# Installation de Composer
php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
php -r "if (hash_file('SHA384', 'composer-setup.php') === '55d6ead61b29c7bdee5cccfb50076874187bd9f21f65d8991d46ec5cc90518f447387fb9f76ebae1fbbacf329e583e30') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
php composer-setup.php
php -r "unlink('composer-setup.php');"
mv composer.phar "$PROJECT_FOLDER/composer.phar"

# Création des dossier Cache et Log de Symfony
sudo mkdir -p /var/tmp/projetX/cache
sudo chown -R www-data:www-data /var/tmp/projetX
sudo chmod -R 777 /var/tmp/projetX
sudo mkdir -p /var/log/projetX
sudo chown www-data:www-data /var/log/projetX
sudo chmod -R 777 /var/log/projetX

# Ecriture du fichier de résultat
touch "$RESULT_FILE_PATH"
echo "Serveur Apache" >> "$RESULT_FILE_PATH"
/usr/sbin/apache2 -v >> "$RESULT_FILE_PATH"
echo "" >> "$RESULT_FILE_PATH"
echo "PHP" >> "$RESULT_FILE_PATH"
php -v >> "$RESULT_FILE_PATH"
echo "" >> "$RESULT_FILE_PATH"
echo "MariaDB - Mot de passe de l'utilisateur root : $PASSWORD" >> "$RESULT_FILE_PATH"
mysql --version >> "$RESULT_FILE_PATH"
echo "" >> "$RESULT_FILE_PATH"
echo "PHPMyAdmin - Mot de passe : $PASSWORD" >> "$RESULT_FILE_PATH"
dpkg -s phpmyadmin | grep 'Version' >> "$RESULT_FILE_PATH"
echo "" >> "$RESULT_FILE_PATH"
echo "Composer" >> "$RESULT_FILE_PATH"
php "$PROJECT_FOLDER/composer.phar" -V >> "$RESULT_FILE_PATH"
