#!/bin/bash
sudo add-apt-repository --remove ppa:ondrej/php -y
sudo add-apt-repository --remove ppa:ondrej/apache2 -y
sudo apt-get purge php* -y
sudo service apache2 stop && sudo apt-get purge apache2 -y
sudo apt-get autoremove -y && sudo apt-get autoclean -y
sudo rm -rf /var/www/html/*