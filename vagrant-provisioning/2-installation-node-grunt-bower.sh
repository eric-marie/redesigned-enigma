#!/usr/bin/env bash

PROJECT_FOLDER='/home/vagrant/src'
SH_FILE_PATH="$PROJECT_FOLDER/vagrant-provisioning/2-installation-node-grunt-bower.sh"
RESULT_FILE_PATH=${SH_FILE_PATH/.sh/.result}
if [ -f "$RESULT_FILE_PATH" ]
then
    echo "$SH_FILE_PATH a deja ete execute. Voir le rapport dans $RESULT_FILE_PATH"
	exit
fi

# Installation de node.js
wget https://deb.nodesource.com/setup_6.x
sudo ./setup_6.x
sudo apt-get install -y nodejs

if [ -d "/usr/bin/node" ]
then
    sudo ln -s /usr/bin/nodejs /usr/bin/node
fi

# Installation de Grunt & Bower
sudo npm install -g grunt
sudo npm install -g bower

# Ecriture du fichier de résultat
touch "$RESULT_FILE_PATH"
echo "Node.js" >> "$RESULT_FILE_PATH"
nodejs -v >> "$RESULT_FILE_PATH"
echo "" >> "$RESULT_FILE_PATH"
echo "NPM" >> "$RESULT_FILE_PATH"
npm -v >> "$RESULT_FILE_PATH"
echo "" >> "$RESULT_FILE_PATH"
echo "Grunt" >> "$RESULT_FILE_PATH"
grunt --version >> "$RESULT_FILE_PATH"
echo "" >> "$RESULT_FILE_PATH"
echo "Bower" >> "$RESULT_FILE_PATH"
bower --version >> "$RESULT_FILE_PATH"