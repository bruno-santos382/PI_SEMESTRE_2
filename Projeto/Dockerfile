# Use the php:8.1-apache base image
FROM php:8.1-apache

# Instalar apenas as extensões PDO e PDO_MySQL
RUN docker-php-ext-install pdo pdo_mysql

# Instalar e habilitar Xdebug
RUN pecl install xdebug && \
    docker-php-ext-enable xdebug

# Habilitar o módulo mod_rewrite do Apache
RUN a2enmod rewrite

# Expor a porta 80 para o Apache
EXPOSE 80

# Comando para rodar o Apache em primeiro plano
CMD ["apache2-foreground"]