FROM php:8.4.10-fpm

ARG UID
ARG GID
ARG APP_PHP_INI_PATH

ENV UID=${UID}
ENV GID=${GID}
ENV APP_PHP_INI_PATH=${APP_PHP_INI_PATH}

RUN mkdir -p /var/www/html

WORKDIR /var/www/html

COPY php-fpm/zz-docker.conf /usr/local/etc/php-fpm.d/
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

# MacOS staff group's gid is 20, so is the dialout group in alpine linux. We're not using it, let's just remove it.
RUN delgroup dialout

# RUN addgroup -g ${GID} --system laravel
# RUN adduser -G laravel --system -D -s /bin/sh -u ${UID} laravel
RUN addgroup --gid ${GID} --system laravel
RUN adduser --ingroup laravel --system --disabled-login -shell /bin/sh -u ${UID} laravel

RUN sed -i "s/user = www-data/user = laravel/g" /usr/local/etc/php-fpm.d/www.conf
RUN sed -i "s/group = www-data/group = laravel/g" /usr/local/etc/php-fpm.d/www.conf
RUN echo "php_admin_flag[log_errors] = on" >> /usr/local/etc/php-fpm.d/www.conf

RUN cp ${APP_PHP_INI_PATH} /usr/local/etc/php/php.ini
ADD ./php/my.ini /usr/local/etc/php/conf.d/

RUN apt-get update && apt-get upgrade -y
RUN apt-get install -y libzip-dev

RUN docker-php-ext-install pdo pdo_mysql exif zip

RUN pecl install redis && docker-php-ext-enable redis

USER laravel

CMD ["php-fpm", "-y", "/usr/local/etc/php-fpm.conf", "-R"]
