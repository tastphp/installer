#!/usr/bin/env bash
cd /var/www
wget https://github.com/tastphp/tastphp/archive/v1.1.0.zip
unzip v1.1.0.zip
rm v1.1.0.zip
cd tastphp-1.1.0
composer install
echo "You have successfully installed Tastphp!"