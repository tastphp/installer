#!/usr/bin/env bash
app_version=1.3.6
cd /var/www
wget https://github.com/tastphp/tastphp/archive/v${app_version}.zip
unzip v${app_version}.zip
rm v${app_version}.zip
cd tastphp-${app_version}
composer install && composer update
echo "You have successfully installed Tastphp!"