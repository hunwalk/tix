FROM php:8.1-fpm-alpine

RUN docker-php-ext-install pdo pdo_mysql

RUN mkdir /scripts
RUN chmod -R 777 /scripts

COPY . /var/www/html
COPY ./docker/app/entrypoint.sh /scripts/entrypoint.sh

RUN chmod +x /scripts/entrypoint.sh

WORKDIR /var/www/html

# Install composer
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
    && php composer-setup.php --install-dir=/usr/local/bin --filename=composer \
    && php -r "unlink('composer-setup.php');"

CMD ["sh","/scripts/entrypoint.sh"]
