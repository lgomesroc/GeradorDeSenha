# Use a imagem oficial do PHP com FPM como base
FROM php:8.2-fpm

# Configure o diretório de trabalho
WORKDIR /var/www

# Atualize os pacotes do sistema
RUN apt-get update

# Instale bibliotecas de imagem
RUN apt-get install -y libpng-dev libjpeg-dev libfreetype6-dev

# Instale utilitários de compactação
RUN apt-get install -y zip unzip

# Instale ferramentas de controle de versão e rede
RUN apt-get install -y git curl

# Instale a biblioteca oniguruma (necessária para mbstring)
RUN apt-get install -y libonig-dev

# Instale o servidor Nginx
RUN apt-get install -y nginx

# Crie o diretório para o Swagger UI
RUN mkdir -p /usr/share/nginx/html/swagger-ui

# Baixe o Swagger UI
RUN curl -L -o /usr/share/nginx/html/swagger-ui/swagger-ui.zip \
    https://github.com/swagger-api/swagger-ui/archive/refs/tags/v4.15.5.zip

# Verifique o diretório antes de descompactar
RUN ls -l /usr/share/nginx/html/swagger-ui/

# Extraia os arquivos do Swagger UI
RUN unzip /usr/share/nginx/html/swagger-ui/swagger-ui.zip -d /usr/share/nginx/html/swagger-ui/ || echo "Erro no unzip"

# Mova os arquivos extraídos para o diretório correto
RUN mv /usr/share/nginx/html/swagger-ui/swagger-ui-4.15.5/dist/* /usr/share/nginx/html/swagger-ui/ || echo "Erro ao mover arquivos"

# Limpe os arquivos temporários do Swagger UI
RUN rm -rf /usr/share/nginx/html/swagger-ui/swagger-ui-4.15.5/ || echo "Erro ao remover diretório temporário"
RUN rm /usr/share/nginx/html/swagger-ui/swagger-ui.zip || echo "Erro ao remover zip"

# Configure e instale a extensão GD
RUN docker-php-ext-configure gd --with-freetype --with-jpeg
RUN docker-php-ext-install gd

# Instale extensões PHP separadamente
RUN docker-php-ext-install pdo_mysql
RUN docker-php-ext-install mbstring
RUN docker-php-ext-install bcmath

# Limpeza para reduzir o tamanho da imagem
RUN apt-get clean && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

# Copie o Composer diretamente de sua imagem oficial
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Copie os arquivos do projeto para o contêiner
COPY . .

# Instale as dependências do Composer
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Ajuste permissões do diretório de trabalho
RUN chown -R www-data:www-data /var/www && chmod -R 755 /var/www

# Exponha a porta usada pelo Flight PHP
EXPOSE 9000
EXPOSE 8080
# Swagger UI será exposto nesta porta

# Comando para iniciar o servidor PHP embutido
CMD ["php", "-S", "0.0.0.0:9000", "-t", "public"]
