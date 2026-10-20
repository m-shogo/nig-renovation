FROM wordpress:6.9-php8.3-apache

RUN apt-get update \
 && apt-get install -y --no-install-recommends mariadb-client less \
 && rm -rf /var/lib/apt/lists/* \
 && curl -fsSL -o /usr/local/bin/wp https://raw.githubusercontent.com/wp-cli/builds/gh-pages/phar/wp-cli.phar \
 && chmod +x /usr/local/bin/wp

COPY php.ini /usr/local/etc/php/conf.d/zz-nig.ini
COPY apache.conf /etc/apache2/sites-available/000-default.conf
RUN echo 'ServerName localhost' > /etc/apache2/conf-enabled/servername.conf
