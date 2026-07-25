# --- Étape 1 : installation des dépendances Composer ---
FROM composer:2 AS vendor
WORKDIR /app
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-scripts --no-autoloader --ignore-platform-reqs

# --- Étape 2 : image d'exécution ---
FROM php:8.2-cli
WORKDIR /app

RUN apt-get update && apt-get install -y --no-install-recommends \
        libzip-dev unzip \
    && docker-php-ext-install pdo_mysql zip \
    && rm -rf /var/lib/apt/lists/*

COPY --from=vendor /usr/bin/composer /usr/bin/composer
COPY --from=vendor /app/vendor ./vendor
COPY . .

RUN composer dump-autoload --optimize --no-dev

EXPOSE 8080

# Migre la base au démarrage puis sert l'application sur le port fourni par l'hébergeur.
CMD php artisan migrate --force && php artisan serve --host=0.0.0.0 --port=${PORT:-8080}
