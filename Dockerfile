FROM php:8.2-apache

# Extensión de PostgreSQL para PHP (pdo_pgsql)
RUN apt-get update \
    && apt-get install -y --no-install-recommends libpq-dev \
    && docker-php-ext-install pdo_pgsql \
    && rm -rf /var/lib/apt/lists/*

# Render asigna el puerto con la variable PORT
ENV PORT=10000
RUN sed -i 's/Listen 80/Listen ${PORT}/' /etc/apache2/ports.conf \
    && sed -i 's/:80>/:${PORT}>/' /etc/apache2/sites-available/000-default.conf

COPY public/ /var/www/html/
EXPOSE 10000
