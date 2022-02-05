#!/bin/bash
#Let’s first create a new script file:
touch /home/hafrica_crons/cronJobScript.sh
#The first thing our script will do is take a copy of all the current jobs:
crontab -l >crontab_new

#We now have all the previous jobs in the crontab_new file.
#This means we can append our new job to it and then rewrite the crontab by using the edited file as an input argument to the crontab command:
echo "30 0 * * * /usr/bin/php /var/www/html/cronJobs/index.php" >>crontab_new
crontab crontab_new

#Since the crontab_new file is temporary, we can remove it:
rm crontab_new

#We must also remember to use chmod +x to make the script executable:
chmod +x /home/hafrica_crons/cronJobScript.sh
cd /home/hafrica_crons/ ./cronJobScript.sh
