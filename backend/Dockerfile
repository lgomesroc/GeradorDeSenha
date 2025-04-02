# Use a imagem oficial do PHP com FPM como base
FROM php:8.2-fpm

# Instale dependências do sistema
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libonig-dev \
    libzip-dev \
    zip \
    unzip \
    git \
    curl

# Instale extensões PHP necessárias, incluindo PDO MySQL e BCMath
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Instale o Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Configure o diretório de trabalho
WORKDIR /var/www

# Copie o código do backend para o contêiner
COPY . .

# Instale as dependências do Composer
RUN composer install

# Instale PHPUnit para testes automatizados
RUN composer require --dev phpunit/phpunit

# Instale Symfony YAML para manipulação de arquivos .yaml
RUN composer require symfony/yaml

# Conceda permissões ao diretório
RUN chown -R www-data:www-data /var/www

# Exponha a porta para o backend
EXPOSE 9000

# Inicie o servidor PHP embutido
CMD ["php", "-S", "0.0.0.0:9000", "-t", "public"]
