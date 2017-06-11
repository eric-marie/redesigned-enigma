#!/usr/bin/env bash

php composer.phar update
sudo chown -R www-data:www-data /var/tmp/projetX
sudo chmod -R 777 /var/tmp/projetX
sudo chown www-data:www-data /var/log/projetX
sudo chmod -R 777 /var/log/projetX