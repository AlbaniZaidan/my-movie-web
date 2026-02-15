FROM php:5.6-apache

# 1. Point to Debian Archive and ignore expired security signatures
RUN sed -i 's/deb.debian.org/archive.debian.org/g' /etc/apt/sources.list \
    && sed -i 's|security.debian.org/debian-security|archive.debian.org/debian-security|g' /etc/apt/sources.list \
    && sed -i '/stretch-updates/d' /etc/apt/sources.list

# 2. Install dependencies with flags to allow unauthenticated packages
RUN apt-get update && apt-get install -y --force-yes --allow-unauthenticated \
    libmcrypt-dev \
    libzip-dev \
    unzip \
    git \
    && docker-php-ext-install pdo_mysql mcrypt mbstring zip

# 3. Enable Apache mod_rewrite
RUN a2enmod rewrite

# 4. Update Apache DocumentRoot (Fixed the ENV warning)
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public

RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# 5. Install Composer 1.x
COPY --from=composer:1 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html