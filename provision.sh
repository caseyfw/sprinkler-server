#!/bin/bash

# Configure timezone
echo "Australia/Adelaide" > /etc/timezone; dpkg-reconfigure --frontend noninteractive tzdata

# Add PHP 7 PPA
apt-add-repository -y ppa:ondrej/php

# Update package list, do a dist upgrade and remove the unused packages
apt-get update --fix-missing
apt-get -y dist-upgrade
apt-get -y autoremove

# Install dependencies
apt-get -y install php7.0 php-apcu php7.0-cli php7.0-common php7.0-curl \
    php7.0-opcache php7.0-sqlite php-xdebug php7.0-xml php7.0-zip apache2 \
    libapache2-mod-php7.0 sqlite3

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

# Change Apache to run as the vagrant user
sed -i 's/APACHE_RUN_USER=www-data/APACHE_RUN_USER=vagrant/' /etc/apache2/envvars
sed -i 's/APACHE_RUN_GROUP=www-data/APACHE_RUN_GROUP=vagrant/' /etc/apache2/envvars
chown -R vagrant:www-data /var/lock/apache2

# Configure XDebug
HOST=$(ip -4 route show default | grep -Po 'default via \K[\d.]+')
echo -e "; configuration for php xdebug module\n; priority=20\nzend_extension=xdebug.so\nxdebug.max_nesting_level=500\nxdebug.remote_enable=1\nxdebug.remote_port=9000\nxdebug.remote_host=${HOST}" > /etc/php/7.0/mods-available/xdebug.ini

# Update the default virtualhost
a2enmod rewrite
a2dismod vhost_alias

# Restart Apache so those modules come online
service apache2 restart

# Change to the /vagrant folder on login
echo "cd /vagrant" >> /home/vagrant/.bashrc
