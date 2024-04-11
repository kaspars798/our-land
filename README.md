## Prerequisites
- Docker Desktop

## Get git project from repository
-?????????

## Install dependencies on docker
docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v "$(pwd):/var/www/html" \
    -w /var/www/html \
    laravelsail/php83-composer:latest \
    composer install --ignore-platform-reqs

## NPM dependancies

- ./vendor/bin/sail npm run dev

## Open application at

- http://localhost

