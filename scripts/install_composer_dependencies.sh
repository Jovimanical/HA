#!/bin/bash
sudo chown -R ubuntu /var/www/html
sudo chmod -R 0777 /var/www/html/

cd ~
curl -sS https://getcomposer.org/installer -o /tmp/composer-setup.php
sudo php /tmp/composer-setup.php --install-dir=/usr/local/bin --filename=composer

cd /var/www/html
sudo \cp siteConfig/000-default.conf /etc/apache2/sites-available/
curl -sS https://getcomposer.org/installer | php
php composer.phar install

sudo systemctl reload apache2

sudo chown -R www-data:www-data /var/www/html
sudo chmod -R 0777 /var/www/html/
# Add cronJobs to Service
#These are piped through the sort command to remove duplicate lines.
#but we can achieve a less destructive de-duplication with awk:
#cd ~
#(crontab -l; echo "* * * * * /usr/bin/php /var/www/html/cronJobs/index.php")|awk '!x[$0]++'|crontab -
#(crontab -l; echo "* * * * * root /usr/bin/wget -O - http://localhost/cronJobs/index.php")|awk '!x[$0]++'|crontab -
