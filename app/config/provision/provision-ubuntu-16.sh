#!/usr/bin/env bash

apt-get update
apt-get install -y debconf-utils

debconf-set-selections <<< "mysql-server mysql-server/root_password password dev"
debconf-set-selections <<< "mysql-server mysql-server/root_password_again password dev"

apt-get install -y git curl nginx mysql-server mysql-client php7.0-cli php7.0-fpm php7.0-mysql php7.0-xml php7.0-curl php7.0-soap gearman-job-server

if [ -f /etc/nginx/sites-enabled/default ]; then
    rm /etc/nginx/sites-enabled/default
fi

ln -s /vagrant/app/config/provision/nginx-vhost-php-7.conf /etc/nginx/sites-enabled/default

mysql -u root -pdev -e "CREATE DATABASE sesamesocial CHARACTER SET utf8"
mysql -u root -pdev -e "GRANT ALL PRIVILEGES ON sesamesocial.* TO 'sesamesocial'@'localhost' IDENTIFIED BY 'dev'"
mysql -u root -pdev -e "FLUSH PRIVILEGES"

systemctl reload nginx.service

# PHP 7 Gearman extension not yet in repo. Must be compiled
apt-get -y install wget unzip re2c libgearman-dev php7.0-dev
mkdir -p /tmp/install
cd /tmp/install
wget https://github.com/wcgallego/pecl-gearman/archive/master.zip
unzip master.zip
cd pecl-gearman-master
phpize
./configure
make install
echo "extension=gearman.so" > /etc/php/7.0/mods-available/gearman.ini
phpenmod -v ALL -s ALL gearman
rm -rf /tmp/install/pecl-gearman-master
rm /tmp/install/master.zip0

cd ~/
EXPECTED_SIGNATURE=$(wget -q -O - https://composer.github.io/installer.sig)
php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
ACTUAL_SIGNATURE=$(php -r "echo hash_file('SHA384', 'composer-setup.php');")

if [ "$EXPECTED_SIGNATURE" != "$ACTUAL_SIGNATURE" ]
then
    >&2 echo 'ERROR: Invalid installer signature'
    rm composer-setup.php
    exit 1
fi



php composer-setup.php --quiet --install-dir=/usr/bin --filename=composer
RESULT=$?
rm composer-setup.php

cd /vagrant && composer install
