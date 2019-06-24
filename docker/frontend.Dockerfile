FROM alpine:3.9

# Install packages
RUN apk --no-cache add nginx supervisor curl nodejs npm

# Configure nginx
COPY config/frontend/nginx.conf /etc/nginx/nginx.conf

# Configure supervisord
COPY config/frontend/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

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