#!/bin/sh

## Application vars
DB_ROOT_PASS="password"
DB_NAME="poeuniqueladder"
DB_USER="poeuniqueladder"
DB_PASS="password"

HOST_NAME="poeuniqueladder"

## Update packages
apt-get -y update
apt-get -y install software-properties-common
add-apt-repository -y ppa:ondrej/php
apt-get -y update

## Don't prompt for MySQL password choice during installation
echo mysql-server mysql-server/root_password password $DB_ROOT_PASS | debconf-set-selections
echo mysql-server mysql-server/root_password_again password $DB_ROOT_PASS | debconf-set-selections

## Install necessary packages
apt-get -y install \
supervisor \
apache2 \
libapache2-mod-php7.2 \
beanstalkd \
build-essential \
curl \
fail2ban \
git \
memcached \
mysql-server \
mysql-client \
php7.2 \
php7.2-cli \
php7.2-curl \
php7.2-json \
php7.2-mysql \
php7.2-mbstring \
php7.2-sqlite3 \
phpunit \
php7.2-zip \
php7.2-gd \
php7.2-xml \
libxrender1 \
libxext6 \
ssl-cert \
texlive-extra-utils

/etc/init.d/beanstalkd start

## Install Composer
curl -sS https://getcomposer.org/installer | php && mv composer.phar /usr/local/bin/composer

## Setup firewall
ufw --force enable
ufw logging on
ufw allow 22
ufw allow 80
ufw allow 443

## Create database and user
mysql -u root -p"$DB_ROOT_PASS" -Bse "CREATE DATABASE $DB_NAME"
mysql -u root -p"$DB_ROOT_PASS" -Bse "GRANT ALL ON $DB_NAME.* to $DB_USER@localhost IDENTIFIED BY '$DB_PASS'"
mysql -u root -p"$DB_ROOT_PASS" -Bse "FLUSH PRIVILEGES"

## Allow for remote connections
sed -i "s/^bind-address/bind-address\t\t= 0.0.0.0 #/" /etc/mysql/mysql.conf.d/mysqld.cnf

## Restart MySQL
service mysql restart

## Setup Apache VirtualHosts
# HTTP version that just redirects to HTTPS
cat > /etc/apache2/sites-available/$HOST_NAME.conf <<DELIM
<VirtualHost *:80>
	ServerName $HOST_NAME
	RewriteEngine on
	RewriteRule (.*) https://%{HTTP_HOST} [R=301,QSA,L]
</VirtualHost>
DELIM

## HTTPS site
cat > /etc/apache2/sites-available/$HOST_NAME-ssl.conf <<DELIM
<VirtualHost *:443>
	ServerName $HOST_NAME
	DocumentRoot /var/www/$HOST_NAME/code/public
	<Directory "/var/www/$HOST_NAME/code/public">
		Options Indexes FollowSymLinks MultiViews
		AllowOverride all
	</Directory>
	SSLEngine on
	SSLCertificateFile /etc/ssl/certs/ssl-cert-snakeoil.pem
	SSLCertificateKeyFile /etc/ssl/private/ssl-cert-snakeoil.key
</VirtualHost>
DELIM

## Set ServerName in Apache config
echo "ServerName $HOST_NAME" >> /etc/apache2/apache2.conf

## Enable sites
a2dissite 000-default
a2ensite $HOST_NAME.conf $HOST_NAME-ssl.conf

## Enable the necessary Apache modules
a2enmod expires rewrite ssl

## Restart Apache
service apache2 restart

## beanstalkd
cat > /etc/default/beanstalkd <<DELIM
## Defaults for the beanstalkd init script, /etc/init.d/beanstalkd on
## Debian systems. Append ``-b /var/lib/beanstalkd'' for persistent
## storage.
BEANSTALKD_LISTEN_ADDR=0.0.0.0
BEANSTALKD_LISTEN_PORT=11300
DAEMON_OPTS="-l \$BEANSTALKD_LISTEN_ADDR -p \$BEANSTALKD_LISTEN_PORT"

## Uncomment to enable startup during boot.
START=yes
DELIM

/etc/init.d/beanstalkd start

## Supervisor, replaces Upstart
cat >> /etc/supervisor/conf.d/laravel-worker.conf <<DELIM
[program:laravel-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/$HOST_NAME/code/artisan queue:work beanstalkd --sleep=3 --tries=3 --timeout=90 --daemon
autostart=true
autorestart=true
user=www-data
numprocs=8
redirect_stderr=true
stdout_logfile=/var/log/laravel-worker.log
DELIM

service supervisor start
supervisord -c /etc/supervisor/supervisord.conf
supervisorctl -c /etc/supervisor/supervisord.conf reload
supervisorctl -c /etc/supervisor/supervisord.conf start laravel-worker:*

cd /var/www/$HOST_NAME/code
composer install

## CRON jobs
cat >> /etc/crontab <<DELIM
* * * * * root php /var/www/$HOST_NAME/code/artisan schedule:run >> /dev/null 2>&1
DELIM

## Development only
apt-get -y install npm
npm install -g n cross-env
n 12.0.0
ln -sf /usr/local/n/versions/node/12.0.0/bin/node /usr/bin/node
npm install npm@latest -g

cd /var/www/$HOST_NAME/code
npm install -g yarn
yarn install
yarn run dev

php artisan key:generate
php artisan migrate
php artisan db:seed

mysql -u root -p"$DB_ROOT_PASS" -Bse "GRANT ALL ON $DB_NAME.* to $DB_USER@'%.%.%.%' IDENTIFIED BY '$DB_PASS'"
mysql -u root -p"$DB_ROOT_PASS" -Bse "GRANT ALL ON $DB_NAME.* to root@'%.%.%.%' IDENTIFIED BY '$DB_ROOT_PASS'"
mysql -u root -p"$DB_ROOT_PASS" -Bse "FLUSH PRIVILEGES"
