FROM php:8.1-apache

# Activer PDO MySQL
RUN docker-php-ext-install pdo pdo_mysql

# Copier le code de l'app dans le container
COPY . /var/www/html/

# Travailler depuis le dossier racine de l'app
WORKDIR /var/www/html/public

EXPOSE 80
