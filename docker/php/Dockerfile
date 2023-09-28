FROM php:8.2-fpm

# Set working dir
WORKDIR /var/www/jeunot

# Install dependencies
RUN apt-get update \
    && apt-get install -y --no-install-recommends libfreetype6-dev \
        locales \
        apt-utils \
        git \
        curl \
        libicu-dev \
        g++ \
        libpng-dev \
        autoconf \
        zip \
        libpq-dev \
        libxml2-dev \
        libzip-dev \
        libonig-dev \
        libxslt-dev \
        unzip;

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Redis
RUN pecl install redis && docker-php-ext-enable redis

# Install extensions
RUN docker-php-ext-install pdo pdo_pgsql zip opcache exif pcntl intl
RUN docker-php-ext-configure intl
RUN pecl install apcu && docker-php-ext-enable apcu
RUN pecl install xdebug && docker-php-ext-enable xdebug

# Install Node.js
# https://github.com/nodesource/distributions#debian-and-ubuntu-based-distributions
RUN apt-get update && apt-get install -y ca-certificates curl gnupg
RUN mkdir -p /etc/apt/keyrings
RUN curl -fsSL https://deb.nodesource.com/gpgkey/nodesource-repo.gpg.key | gpg --dearmor -o /etc/apt/keyrings/nodesource.gpg
RUN echo "deb [signed-by=/etc/apt/keyrings/nodesource.gpg] https://deb.nodesource.com/node_18.x nodistro main" | tee /etc/apt/sources.list.d/nodesource.list
RUN apt-get update && apt-get install -y nodejs

# Install Symfony cli
RUN curl -1sLf 'https://dl.cloudsmith.io/public/symfony/stable/setup.deb.sh' | bash && \
    apt install symfony-cli

# Install composer
RUN curl -sSk https://getcomposer.org/installer | php -- --disable-tls && \
   mv composer.phar /usr/local/bin/composer

# Allow husky to install pre-commit hooks
RUN git config --global --add safe.directory /var/www/jeunot

CMD ["php-fpm"]
