#!/bin/bash
if ! [ -x "$(command -v apache2)" ]; then

  # Update Package Index
  sudo apt-get update && sudo apt-get upgrade -y
  sudo apt-get install ca-certificates apt-transport-https software-properties-common -y
  sudo add-apt-repository ppa:ondrej/php -y
  sudo add-apt-repository ppa:ondrej/apache2 -y
  sudo apt-get update -y

  # Install Apache2
  sudo apt-get install apache2
  # Allow to run Apache on boot up
  sudo systemctl enable apache2
  # Adjust Firewall
  sudo ufw allow in "Apache Full"
  sudo a2enmod rewrite
  sudo a2dismod mpm_worker
  sudo a2dismod mpm_prefork

  sudo apt-get install wget unzip -y
  # Install PHP
  sudo apt-get install php8.0 libapache2-mod-php8.0 php8.0-mysql php8.0-gd php8.0-xml php8.0-soap php8.0-mbstring php8.0-mysql php8.0-redis php8.0-curl php8.0-cli php8.0-zip php8.0-yaml -y

  #sudo a2enmod php8.0

  # Restart Apache Web Server
  sudo systemctl restart apache2

  # I want to make sure that the directory is clean and has nothing left over from
  # previous deployments. The servers auto scale so the directory may or may not
  # exist.

  sudo rm -rf /var/www/html/*

  # Allow Read/Write for Owner and App to write
  sudo chown www-data:www-data /var/www/html
  sudo chmod -R 0777 /var/www/html/
  sudo usermod -a -G www-data ubuntu
  exit 1
else
  # I want to make sure that the directory is clean and has nothing left over from
  # previous deployments. The servers auto scale so the directory may or may not
  # exist.
  if [ -d /var/www/html ]; then
    sudo rm -rf /var/www/html/*
  fi
fi # install apache if not already installed
