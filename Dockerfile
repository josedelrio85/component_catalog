FROM 952729869933.dkr.ecr.eu-west-1.amazonaws.com/symfony-node:7.2.10-8.12.0

RUN apk add mysql-client

# Set ENV VARS
ENV COMPOSER_VERSION=1.1.0 COMPOSER_ALLOW_SUPERUSER=1 COMPOSER_PATH=/usr/local/bin
ENV SYMFONY_ENV prod
ENV APP_ENV prod
ENV CHECK_LEAD_LEONTEL_ENDPOINT https://ws.bysidecar.es/lead/smartcenter/isopen

WORKDIR /var/www/html
ADD --chown=www-data:www-data . /var/www/html

# Use php helper scripts to install PHP extensions (to reduce image size)
RUN docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ \
 && docker-php-ext-install -j$(nproc) iconv mbstring intl pdo_mysql gd zip bcmath

# Install composer & configure hirak prestissimo to enable parallel installs
RUN curl -sS https://getcomposer.org/installer | \
  php -- --install-dir=${COMPOSER_PATH} --filename=composer --version=${COMPOSER_VERSION} \
  && export COMPOSER_COMMAND="composer" \
  && $COMPOSER_COMMAND global require --quiet "hirak/prestissimo:^0.3"

# Add php & fpm & nginx customized configuration files.
ADD ./ci/conf/php.ini /usr/local/etc/php
ADD ./ci/conf/fpm-pool.conf /usr/local/etc/php-fpm.d/zzz_custom.conf
ADD ./ci/conf/nginx.conf /etc/nginx/nginx.conf

RUN composer install \
  && npm install \
  && npm rebuild node-sass \
  && npm run-script build \
  && composer dump-env prod \
  && php bin/console cache:warmup 

# Add supervisord configuration to run both nginx and fpm.
ADD ./ci/conf/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Update CMD to run supervisord that would run nginx & fpm
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]
