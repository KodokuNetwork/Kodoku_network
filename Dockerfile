FROM php:8.1-apache
# Install ekstensi PHP yang umum dipakai
RUN docker-php-ext-install mysqli pdo pdo_mysql
# Aktifkan .htaccess jika kamu butuh
RUN a2enmod rewrite
# Copy semua file ke dalam container
COPY . /var/www/html/
# Atur permission supaya Apache bisa akses
RUN chown -R www-data:www-data /var/www/html
# Direktori kerja
WORKDIR /var/www/html
