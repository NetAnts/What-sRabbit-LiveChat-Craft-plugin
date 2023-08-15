ARG PHP_VERSION
FROM php:${PHP_VERSION}-cli

# Change mirror
RUN sed -i 's/deb.debian.org/ftp.nl.debian.org/g' /etc/apt/sources.list || true
RUN sed -i 's/ftp.debian.org/ftp.nl.debian.org/g' /etc/apt/sources.list || true
RUN sed -i 's/deb.debian.org/ftp.nl.debian.org/g' /etc/apt/sources.list.d/debian.sources || true
RUN sed -i 's/ftp.debian.org/ftp.nl.debian.org/g' /etc/apt/sources.list.d/debian.sources || true

# Install packages
RUN apt-get update && DEBIAN_FRONTEND=noninteractive apt-get install -yq \
        zip \
        unzip \
        libzip-dev \
        libicu-dev

# Install PCOV extension, we need this to generate phpunit's code coverage report
RUN pecl install pcov && docker-php-ext-enable pcov
RUN docker-php-ext-install bcmath
RUN docker-php-ext-install zip
RUN docker-php-ext-configure intl && docker-php-ext-install intl
RUN docker-php-ext-install pdo pdo_mysql

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

# Configure the environment for a separate user to run Apache
ARG HOST_UID=1000
ARG HOST_GID=1000

RUN chown --recursive ${HOST_UID}:${HOST_GID} /var/www
RUN addgroup --gid ${HOST_GID} netants; \
    adduser netants --uid ${HOST_UID} --gid ${HOST_GID} --shell /bin/bash --home /var/www/html --disabled-password --gecos "";

VOLUME ["/var/www/html"]
WORKDIR "/var/www/html"

USER ${HOST_UID}
