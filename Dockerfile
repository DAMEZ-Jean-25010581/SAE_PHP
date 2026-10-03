FROM php:8.3-apache

# Driver MySQL / MariaDB pour PDO
RUN docker-php-ext-install pdo_mysql

# Le site public est dans public/ (app/ reste hors d'atteinte du navigateur)
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf \
    && sed -ri 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Modules utilisés par le .htaccess, et autorisation des .htaccess
RUN a2enmod rewrite headers expires \
    && sed -i 's/AllowOverride None/AllowOverride All/' /etc/apache2/apache2.conf
