# Naudojame oficialų PHP 7.4 atvaizdą su Apache serveriu
FROM php:7.4-apache

# Įdiegiame sistemos priklausomybes, reikalingas PHP plėtiniams
RUN apt-get update && \
    apt-get install -y \
      libicu-dev \
      libonig-dev \
      libzip-dev \
      zip \
      unzip \
      sqlite3 \
      libsqlite3-dev && \
    rm -rf /var/lib/apt/lists/*

# Įdiegiame reikalingus PHP plėtinius
RUN docker-php-ext-install intl mbstring pdo_sqlite zip

# Įdiegiame Composer (globaliai)
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Nustatome darbo direktoriją
WORKDIR /var/www/html

# Kopijuojame projekto failus į kontejnerį
# Naudosime docker-compose.yml, kad prijungtume (mount) kodą,
# todėl šis žingsnis skirtas tik pradiniam diegimui.
COPY boilerplate/ .

# Įdiegiame projekto priklausomybes
RUN composer install --no-interaction --optimize-autoloader

# Suteikiame rašymo teises 'tmp' ir 'logs' katalogams
RUN chown -R www-data:www-data tmp logs

# Įjungiame Apache mod_rewrite
RUN a2enmod rewrite

# Kopijuojame ir nustatome entrypoint skriptą
COPY docker-entrypoint.sh /usr/local/bin/
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

# Atidengiame 80 portą
EXPOSE 80

# Nustatome entrypoint, kuris paleis migracijas ir seeds prieš Apache
ENTRYPOINT ["/usr/local/bin/docker-entrypoint.sh"]

