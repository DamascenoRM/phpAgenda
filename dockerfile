FROM php:8.3-alpine

# Installing bash
RUN apk add bash
RUN sed -i 's/bin\/ash/bin\/bash/g' /etc/passwd

# Installing pdo
RUN docker-php-ext-install pdo pdo_mysql
RUN docker-php-ext-install mysqli && docker-php-ext-enable mysqli


# Installing composer
RUN php -r "readfile('http://getcomposer.org/installer');" | php -- --install-dir=/usr/local/bin --filename=composer
RUN alias composer='php /usr/local/bin/composer'

EXPOSE 80
ENTRYPOINT ["docker-php-entrypoint"]
#CMD "php" "-a"