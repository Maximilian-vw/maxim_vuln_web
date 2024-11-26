# Menggunakan image resmi Apache
FROM httpd:2.4

# Menyalin kode sumber dari repositori
COPY ./src/ /usr/local/apache2/htdocs/

# Install PHP 5.6.40
RUN apt-get update && \
    apt-get install -y software-properties-common && \
    add-apt-repository ppa:ondrej/php && \
    apt-get update && \
    apt-get install -y php5.6 libapache2-mod-php5.6 && \
    rm -rf /var/lib/apt/lists/*

# Konfigurasi PHP
RUN docker-php-ext-install mysqli pdo_mysql zip

# Konfigurasi Apache
RUN a2enmod rewrite

# Jalankan Apache
CMD ["apache2ctl", "-D", "FOREGROUND"]