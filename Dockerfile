FROM php:8.2-apache

RUN apt-get update \
    && apt-get install -y --no-install-recommends unzip libcurl4-openssl-dev \
    && docker-php-ext-install mysqli curl \
    && a2enmod rewrite headers \
    && rm -rf /var/lib/apt/lists/*

WORKDIR /var/www/html
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
COPY composer.json ./
RUN composer install --no-dev --prefer-dist --no-interaction --optimize-autoloader

COPY . .
RUN mkdir -p uploads \
    && chown -R www-data:www-data uploads \
    && chmod -R 750 uploads

EXPOSE 80
HEALTHCHECK --interval=30s --timeout=5s --start-period=20s --retries=3 \
  CMD php -r "exit(@file_get_contents('http://127.0.0.1/health.php') === false ? 1 : 0);"
