FROM php:7.4-apache

RUN apt-get update -y && apt-get install -y sendmail libpng-dev libfreetype6-dev libjpeg62-turbo-dev libgd-dev libpng-dev

RUN apt-get install -y libicu-dev

RUN docker-php-ext-install pdo pdo_mysql mysqli

RUN docker-php-ext-configure gd \
--with-freetype=/usr/include/ \
--with-jpeg=/usr/include/

RUN docker-php-ext-install gd

RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
    php -r "if (hash_file('SHA384', 'composer-setup.php') === '544e09ee996cdf60ece3804abc52599c22b1f40f4323403c44d44fdfdd586475ca9813a858088ffbc1f233e9b180f061') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;" \
    php composer-setup.php --install-dir=/bin \
    php -r "unlink('composer-setup.php');" \

RUN touch /usr/local/etc/php/conf.d/uploads.ini \
	&& echo "upload_max_filesize = 10M;" >> /usr/local/etc/php/conf.d/uploads.ini

RUN docker-php-ext-enable mysqli
RUN docker-php-ext-configure intl
RUN docker-php-ext-install intl

RUN usermod -u 1000 www-data && groupmod -g 1000 www-data

RUN printf '[PHP]\ndate.timezone = "Europe/Prague"\n' > /usr/local/etc/php/conf.d/tzone.ini
RUN touch /usr/local/etc/php/conf.d/uploads.ini \
    && echo "upload_max_filesize = 1G;" >> /usr/local/etc/php/conf.d/uploads.ini \
    && echo "upload_max_filesize = 1G;"  >> /usr/local/etc/php/conf.d/uploads.ini \
    && echo "post_max_size = 1G;"  >> /usr/local/etc/php/conf.d/uploads.ini

RUN a2enmod rewrite
RUN a2enmod headers

COPY . /var/www/html/
WORKDIR /var/www/html/
