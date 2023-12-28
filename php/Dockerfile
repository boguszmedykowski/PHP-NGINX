FROM php:7.4-fpm

# Aktualizacja pakietów i instalacja sendmail, git, unzip oraz niezbędnych bibliotek dla rozszerzenia zip
RUN apt-get update && \
    apt-get install -y sendmail git unzip libzip-dev && \
    docker-php-ext-install zip && \
    apt-get clean && \
    rm -rf /var/lib/apt/lists/*

# Instalacja Composera
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Ustawienie katalogu roboczego
WORKDIR /var/www/html

# Najpierw kopiuj tylko plik composer.json (i composer.lock jeśli istnieje)
COPY composer.json composer.lock* /var/www/html/

# Ustawienie zmiennej środowiskowej, aby pozwolić Composerowi na uruchamianie jako root
ENV COMPOSER_ALLOW_SUPERUSER=1

# Instalacja zależności za pomocą Composera
RUN composer install --no-interaction --prefer-dist

# Teraz kopiowanie reszty plików projektu
COPY . /var/www/html
