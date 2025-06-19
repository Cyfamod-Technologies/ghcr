FROM php:8.2-cli

WORKDIR /app


COPY index.php .


CMD ["php", "-S", "0.0.0.0:80", "index.php"]
