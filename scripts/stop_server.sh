#!/bin/bash
server_status=$(service apache2 status)

if [[ $server_status == *"active (running)"* ]]; then
  echo "apache2 process is running"
  sudo systemctl stop apache2
else
  echo "apache2 process is not running"
fi
