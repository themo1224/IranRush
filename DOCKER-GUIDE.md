 # Docker Guide for Laravel API Project

## Table of Contents
1. [Docker Basics](#docker-basics)
2. [Docker Compose](#docker-compose)
3. [Dockerfile](#dockerfile)
4. [Nginx Configuration](#nginx-configuration)
5. [Environment Setup](#environment-setup)
6. [Common Commands](#common-commands)

## Docker Basics

### What is Docker?
Docker is a platform that allows you to package applications and their dependencies into containers. Containers are lightweight, standalone, and executable packages that include everything needed to run an application.

### Key Concepts
- **Container**: A running instance of an image
- **Image**: A read-only template with instructions for creating a container
- **Volume**: Persistent storage for containers
- **Network**: Communication between containers
- **Dockerfile**: Instructions to build an image
- **docker-compose.yml**: Configuration for running multiple containers

## Docker Compose

Our `docker-compose.yml` file defines three services:

### 1. App Service (PHP)
```yaml
app:
  build:
    context: .
    dockerfile: Dockerfile
  container_name: iran-rush-api
  restart: unless-stopped
  working_dir: /var/www/
  volumes:
    - ./:/var/www
  networks:
    - iran-rush-network
```

**Explanation:**
- `build`: Specifies how to build the image
  - `context`: Directory containing the Dockerfile
  - `dockerfile`: Name of the Dockerfile
- `container_name`: Custom name for the container
- `restart`: Container restart policy
- `working_dir`: Default working directory
- `volumes`: Mounts host directories to container
- `networks`: Connects container to specified network

### 2. Nginx Service
```yaml
nginx:
  image: nginx:alpine
  container_name: iran-rush-nginx
  restart: unless-stopped
  ports:
    - "8000:80"
  volumes:
    - ./:/var/www
    - ./docker/nginx:/etc/nginx/conf.d/
  networks:
    - iran-rush-network
```

**Explanation:**
- `image`: Uses pre-built nginx:alpine image
- `ports`: Maps host port 8000 to container port 80
- `volumes`: Mounts application and nginx config

### 3. MySQL Service
```yaml
db:
  image: mysql:8.0
  container_name: iran-rush-db
  restart: unless-stopped
  environment:
    MYSQL_DATABASE: iran_rush
    MYSQL_ROOT_PASSWORD: root
    MYSQL_PASSWORD: secret
    MYSQL_USER: iran_rush
  ports:
    - "3306:3306"
  volumes:
    - dbdata:/var/lib/mysql/
  networks:
    - iran-rush-network
```

**Explanation:**
- `environment`: Sets database credentials
- `volumes`: Persistent storage for database
- `ports`: Maps MySQL port

### Networks and Volumes
```yaml
networks:
  iran-rush-network:
    driver: bridge

volumes:
  dbdata:
    driver: local
```

**Explanation:**
- `networks`: Creates isolated network for containers
- `volumes`: Defines persistent storage

## Dockerfile

Our `Dockerfile` builds the PHP environment:

```dockerfile
FROM php:8.2-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

# Copy existing application directory
COPY . .

# Install dependencies
RUN composer install

# Change ownership
RUN chown -R www-data:www-data /var/www

# Expose port 9000
EXPOSE 9000

CMD ["php-fpm"]
```

**Explanation:**
- `FROM`: Base image (PHP 8.2 with FPM)
- `RUN`: Executes commands in the container
- `COPY`: Copies files from host to container
- `WORKDIR`: Sets working directory
- `EXPOSE`: Declares port to be exposed
- `CMD`: Default command to run

## Nginx Configuration

Our Nginx config (`docker/nginx/app.conf`):

```nginx
server {
    listen 80;
    index index.php index.html;
    error_log  /var/log/nginx/error.log;
    access_log /var/log/nginx/access.log;
    root /var/www/public;
    location ~ \.php$ {
        try_files $uri =404;
        fastcgi_split_path_info ^(.+\.php)(/.+)$;
        fastcgi_pass app:9000;
        fastcgi_index index.php;
        include fastcgi_params;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        fastcgi_param PATH_INFO $fastcgi_path_info;
    }
    location / {
        try_files $uri $uri/ /index.php?$query_string;
        gzip_static on;
    }
}
```

**Explanation:**
- `listen`: Port to listen on
- `root`: Document root
- `location`: URL routing rules
- `fastcgi_pass`: PHP-FPM service connection

## Environment Setup

1. Create required directories:
```bash
mkdir -p docker/nginx
```

2. Update `.env`:
```
DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=iran_rush
DB_USERNAME=iran_rush
DB_PASSWORD=secret
```

## Common Commands

### Build and Start
```bash
# Build and start all containers
docker-compose up -d --build

# View logs
docker-compose logs -f

# Stop containers
docker-compose down
```

### Container Management
```bash
# Enter PHP container
docker-compose exec app bash

# Run artisan commands
docker-compose exec app php artisan migrate

# View container status
docker-compose ps
```

### Database
```bash
# Access MySQL
docker-compose exec db mysql -u iran_rush -p

# Backup database
docker-compose exec db mysqldump -u iran_rush -p iran_rush > backup.sql
```

### Troubleshooting
```bash
# View container logs
docker-compose logs [service_name]

# Rebuild specific service
docker-compose up -d --build [service_name]

# Remove all containers and volumes
docker-compose down -v
```

## Best Practices
1. Use `.dockerignore` to exclude unnecessary files
2. Keep containers stateless
3. Use environment variables for configuration
4. Implement proper logging
5. Use named volumes for persistent data
6. Keep images small and efficient
7. Use specific version tags for images
8. Implement health checks
9. Use multi-stage builds when possible
10. Follow security best practices