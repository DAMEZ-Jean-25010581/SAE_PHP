FROM php:8.3-apache

# Driver MySQL / MariaDB pour PDO
RUN docker-php-ext-install pdo_mysql

# Modules utilisés par le .htaccess, et autorisation des .htaccess
RUN a2enmod rewrite headers expires \
    && sed -i 's/AllowOverride None/AllowOverride All/' /etc/apache2/apache2.conf
