#!/usr/bin/env bash

echo "#stop backend"
sudo service apache2 stop
##########################

echo "#stop bot"
sudo systemctl stop bot
##########################

echo "#pull backend"
cd /var/www/html/SmartFootball
sudo git checkout master
sudo git pull
echo "replace env file"
ls ./
ls -a ./deploy/prod/
sudo mv ./deploy/prod/env ./
sudo mv env .env

echo "#start backend"
sudo service apache2 start


echo "#pull bot "
cd /var/www/football_telegram_bot
sudo git checkout master && sudo git pull

echo "#restart bot"
sudo systemctl start bot
