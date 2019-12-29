FROM php:7.3-fpm-alpine
WORKDIR /var/www
RUN apk update
RUN apk add git curl zip

# install php extensions
ADD https://raw.githubusercontent.com/mlocati/docker-php-extension-installer/master/install-php-extensions /usr/local/bin/
RUN chmod uga+x /usr/local/bin/install-php-extensions && sync && install-php-extensions pdo_mysql intl xdebug

# install composer
ADD https://getcomposer.org/installer /bin/composer-setup.php
RUN php /bin/composer-setup.php --quiet --install-dir=/usr/bin
RUN rm /bin/composer-setup.php
RUN ln -s /usr/bin/composer.phar /usr/bin/composer

# copy ssh key for cds composer repository
COPY /docker/php-fpm/id_rsa /docker/php-fpm/id_rsa.pub /root/.ssh/
RUN chmod 700 /root/.ssh && chmod 644 /root/.ssh/id_rsa.pub && chmod 600 /root/.ssh/id_rsa

# install dependencies via composer
COPY composer.json composer.lock ./
RUN composer install --no-scripts

CMD ["php-fpm"]
EXPOSE 9000