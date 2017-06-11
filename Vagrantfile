# -*- mode: ruby -*-
# vi: set ft=ruby :

Vagrant.configure("2") do |config|
  # La box utilisée (nom donné à l'installation de la box dans Vagrant)
  config.vm.box = "debian-jessie"

  # Partage le dossier du projet avec la VM
  config.vm.synced_folder "./", "/home/vagrant/src", create: true, owner: "vagrant", group: "vagrant"

  # Aller sur "localhost:8080" redirige vers le port 80 sur la VM
  config.vm.network "forwarded_port", guest: 80, host: 8080

  # Permet d'accéder à la box par cette IP uniquement depuis ce PC via un réseau privé
  config.vm.network "private_network", ip: "192.168.33.10"

  # Provisioning
  # Les fichiers *.sh doivent être enregistrés avec un séparateur de ligne LF au lieu de CRLF
  # L'argument [run: "always"] ne doit pas non plus être oublié
  config.vm.provision "shell", path: "vagrant-provisioning/1-installation-serveur.sh", run: "always"
  config.vm.provision "shell", path: "vagrant-provisioning/2-installation-node-grunt-bower.sh", run: "always"
  config.vm.provision "shell", path: "vagrant-provisioning/3-create-databases/script.sh", run: "always"
  config.vm.provision "shell", path: "vagrant-provisioning/0-always.sh", run: "always"
end
