#!/bin/bash
if ! [ -x "$(command -v apache2)" ]; then

  # Update Package Index
  sudo apt-get update && sudo apt-get upgrade -y
  sudo apt-get install lsb-release ca-certificates apt-transport-https software-properties-common -y
  sudo add-apt-repository ppa:ondrej/php -y
  sudo add-apt-repository ppa:ondrej/apache2 -y
  sudo apt-get update -y
  echo "System updated"

  # Install Apache2
  sudo apt install -y apache2
  echo "Apache2 server installed"
  # Allow to run Apache on boot up
  sudo systemctl enable apache2
  echo "Apache2 server enabled"
  # Adjust Firewall
  sudo ufw allow in "Apache Full"

  sudo apt -y install wget unzip openssl
  # Install PHP
  sudo apt install -y php7.4 libapache2-mod-php7.4 php7.4-gmp php7.4-mysql php7.4-gd php7.4-xml php7.4-soap php7.4-mbstring php7.4-mysql php7.4-redis php7.4-curl php7.4-cli php7.4-zip php7.4-yaml php7.4-common php7.4-bcmath php7.4-json

  echo "Server installed PHP"
  sudo a2enmod rewrite
  sudo a2enmod php7.4

  echo "PHP Activated and restarting Apache2"
  # Restart Apache Web Server
  sudo systemctl restart apache2


  # I want to make sure that the directory is clean and has nothing left over from
  # previous deployments. The servers auto scale so the directory may or may not
  # exist.
  echo "Directories and permission"
  echo "System Checking Directories Exits"
  if [ -d /var/www/html ]; then
      sudo rm -rf /var/www/html/*
      echo "System Directory Cleared"
  fi

  # Allow Read/Write for Owner and App to write
  sudo usermod -aG www-data ubuntu
  sudo addgroup www-data
  sudo chown -R www-data:www-data /var/www/html
  sudo chmod -R 0777 /var/www/html/

  echo "System script Exiting"
  exit 0
else
  # I want to make sure that the directory is clean and has nothing left over from
  # previous deployments. The servers auto scale so the directory may or may not
  # exist.
  echo "System Checking Directories Exits"
  #sudo apt-get update -y
  #sudo apt-get install -y php7.4-gmp openssl

  if [ -d /var/www/html ]; then
    sudo rm -rf /var/www/html/*
    echo "System Directory Cleared"
  fi
fi # install apache if not already installed
