FROM php:8.1-apache
 
RUN a2enmod rewrite
 
RUN apt-get update \
  && apt-get install -y libzip-dev git wget --no-install-recommends \
  && apt-get clean \
  && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*
 
RUN docker-php-ext-install pdo mysqli pdo_mysql zip;
 
RUN wget https://getcomposer.org/download/2.0.9/composer.phar \
    && mv composer.phar /usr/bin/composer && chmod +x /usr/bin/composer
 
COPY docker/apache.conf /etc/apache2/sites-enabled/000-default.conf

RUN sed -i 's,^memory_limit =.*$,memory_limit = 8192M,' /usr/local/etc/php/php.ini-development

RUN sed -i 's,^memory_limit =.*$,memory_limit = 8192M,' /usr/local/etc/php/php.ini-production

RUN echo 'memory_limit = 512M' >> /usr/local/etc/php/conf.d/docker-php-memlimit.ini

COPY . /var/www
 
WORKDIR /var/www

RUN chmod -R 777 /var/www/

RUN composer install
RUN php artisan key:generate

RUN php artisan cache:clear

#Run the unit test cases
RUN ./vendor/bin/phpunit
 
CMD ["apache2-foreground"]
