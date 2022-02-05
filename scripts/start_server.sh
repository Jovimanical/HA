#!/bin/bash
server_status=$(service apache2 status)

if [[ $server_status == *"active (running)"* ]]; then
  echo "apache2 process is running"
  sudo systemctl restart apache2
else
  echo "apache2 process is not running"
  sudo systemctl start apache2
fi
