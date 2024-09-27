#!/bin/bash
# sudo nano setup-init.sh && sudo chmod +x setup-init.sh && sudo . setup-init.sh
# Version 1.1
# Description
# Setting Up ATC V2
# Author: Eskie Maquilang

START=$(date +%s)

# Adding php7.4
apt install -y software-properties-common
add-apt-repository -y ppa:ondrej/php
apt update

apt install -y nginx mysql-server

apt install -y php7.4 php7.4-fpm php7.4-common php7.4-mysql php7.4-xml php7.4-gd php7.4-mbstring php7.4-tokenizer php7.4-json php7.4-bcmath php7.4-zip php7.4-curl unzip
apt install -y php7.4-imagick

php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
php -r "if (hash_file('sha384', 'composer-setup.php') === '906a84df04cea2aa72f40b5f787e49f22d4c2f19492ac310e8cba5b96ac8b64115ac402c8cd292b8a03482574915d1a8') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
php composer-setup.php
php -r "unlink('composer-setup.php');"

mv composer.phar /usr/local/bin/composer

curl -sL https://deb.nodesource.com/setup_14.x | sudo -E bash -

apt update
apt install nodejs

END=$(date +%s)
DIFF=$(( $END - $START ))
echo "It took $DIFF seconds"