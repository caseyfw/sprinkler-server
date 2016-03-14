#!/bin/bash

# Configure timezone
echo "Australia/Adelaide" > /etc/timezone; dpkg-reconfigure tzdata

# Add PHP 7 and Node PPAs
apt-add-repository -y ppa:ondrej/php

# Update package list, do a dist upgrade and remove the unused packages
apt-get update --fix-missing
apt-get -y dist-upgrade
apt-get -y autoremove

# Install PHP and Apache  
apt-get -y install php7.0 php-apcu php7.0-cli php7.0-common php7.0-curl \
    php7.0-opcache php7.0-sqlite php-xdebug php7.0-xml php7.0-zip apache2 \
    libapache2-mod-php7.0

# Configure Apache
APACHE_CONFIG=$(cat <<EOF
<VirtualHost *:80>
    ServerName $(hostname).dev

    ServerAdmin webmaster@localhost
    DocumentRoot /vagrant/web

    <Directory "/vagrant/web">
        AllowOverride All
        Require all granted
    </Directory>

    ErrorLog \${APACHE_LOG_DIR}/error.log
    CustomLog \${APACHE_LOG_DIR}/access.log combined
</VirtualHost>
EOF
)
echo "${APACHE_CONFIG}" > /etc/apache2/sites-available/000-default.conf

# Update the default virtualhost
a2enmod rewrite
a2dismod vhost_alias

# Restart Apache so those modules come online
service apache2 restart

# Change to the /vagrant folder on login
echo "cd /vagrant" >> /home/vagrant/.bashrc