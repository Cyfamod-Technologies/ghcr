FROM php:8.2-cli

LABEL org.opencontainers.image.title="GHCR Hello World"
LABEL org.opencontainers.image.description="A simple Hello World PHP app published to GitHub Container Registry using Docker and GitHub Actions."
LABEL org.opencontainers.image.version="v1.0.0"
LABEL org.opencontainers.image.licenses="MIT"
LABEL org.opencontainers.image.source="https://github.com/incrisz/ghcr-php"
LABEL org.opencontainers.image.documentation="https://github.com/incrisz/ghcr-php"
LABEL org.opencontainers.image.authors="incrisz <incrisz@example.com>"

WORKDIR /app


COPY index.php .


CMD ["php", "-S", "0.0.0.0:80", "index.php"]
