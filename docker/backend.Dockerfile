FROM alpine:3.9

# Install packages
RUN apk --no-cache add php7 \
    php7-fpm php7-mysqli php7-openssl php7-pdo php7-pdo_mysql php7-mbstring \
    php7-json php7-dom php7-xml php7-xmlwriter php7-tokenizer php7-ctype \
    composer nginx supervisor curl

# Configure nginx
COPY config/backend/nginx.conf /etc/nginx/nginx.conf

# Configure PHP-FPM
COPY config/backend/php.ini /etc/php7/conf.d/custom.ini

# Configure supervisord
COPY config/backend/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Make sure files/folders needed by the processes are accessable when they run under the nobody user
RUN chown -R nobody.nobody /run && \
  chown -R nobody.nobody /var/lib/nginx && \
  chown -R nobody.nobody /var/tmp/nginx && \
  chown -R nobody.nobody /var/log/nginx

# Setup document root
RUN mkdir -p /var/www/html

WORKDIR /var/www/html

# Let supervisord start nginx & php-fpm
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]