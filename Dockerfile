# Usando la imagen oficial de PHP con Apache para Laravel
FROM php:latest

# Actualizando la lista de paquetes e instalando dependencias necesarias
RUN apt-get update && apt-get install -y \
    libpq-dev \
    zip \
    unzip \
    && docker-php-ext-install pdo pdo_pgsql

# Configurando el directorio de trabajo
WORKDIR /var/www/html

# Copiando los archivos del proyecto Laravel al contenedor
COPY . .

# Instalando Composer para manejar las dependencias de Laravel
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Copiando el archivo .env proporcionado al contenedor
COPY .env .env

# Instalando las dependencias de Laravel
RUN composer install --ignore-platform-reqs

# Generando la clave de la aplicación
RUN php artisan key:generate

# Exponiendo el puerto 80 para el servidor web Apache
EXPOSE 80

# Comando para iniciar el servidor web
CMD php artisan serve --host=0.0.0.0 --port=80
