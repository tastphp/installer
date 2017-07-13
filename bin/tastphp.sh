#!/usr/bin/env bash
cd /var/www
wget https://github.com/tastphp/tastphp/archive/v1.3.1.zip
unzip v1.3.1.zip
rm v1.3.1.zip
cd tastphp-1.3.1
composer install
echo "You have successfully installed Tastphp!"