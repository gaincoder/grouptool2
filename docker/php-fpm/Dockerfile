FROM php:7.3-fpm-alpine



RUN apk update
RUN apk add gnupg git curl libxml2-dev zlib-dev  icu-dev g++ autoconf nodejs make rsync dos2unix yarn

RUN docker-php-ext-install pdo_mysql intl


RUN pecl install xdebug-2.7.2 && docker-php-ext-enable xdebug

COPY docker-entrypoint.sh /bin/docker-entrypoint.sh
RUN chmod +x /bin/docker-entrypoint.sh
RUN dos2unix /bin/docker-entrypoint.sh
ENTRYPOINT ["/bin/docker-entrypoint.sh"]
CMD ["php-fpm"]

EXPOSE 9000