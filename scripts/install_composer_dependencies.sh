#!/bin/bash
cd ~
sudo apt-get install wget php8.0-cli php8.0-zip php8.0-yaml unzip -y
curl -sS https://getcomposer.org/installer -o /tmp/composer-setup.php
sudo php /tmp/composer-setup.php --install-dir=/usr/local/bin --filename=composer
cd /var/www/html
sudo cp siteConfig/000-default.conf /etc/apache2/sites-available/
curl -sS https://getcomposer.org/installer | php
php composer.phar install

sudo systemctl reload apache2
# Add cronJobs to Service
#These are piped through the sort command to remove duplicate lines.
#but we can achieve a less destructive de-duplication with awk:
#cd ~
#(crontab -l; echo "* * * * * /usr/bin/php /var/www/html/cronJobs/index.php")|awk '!x[$0]++'|crontab -
#(crontab -l; echo "* * * * * root /usr/bin/wget -O - http://localhost/cronJobs/index.php")|awk '!x[$0]++'|crontab -
