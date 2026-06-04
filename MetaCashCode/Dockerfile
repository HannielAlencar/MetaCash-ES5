# Usa a imagem oficial do PHP com Apache
FROM php:8.2-apache

# Atualiza os pacotes e instala as dependências do PostgreSQL
RUN apt-get update && apt-get install -y libpq-dev \
    && docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql \
    && docker-php-ext-install pdo pdo_pgsql pgsql

# Habilita o mod_rewrite do Apache (útil para URLs amigáveis)
RUN a2enmod rewrite
