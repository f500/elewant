#!/usr/bin/env bash

cp -n docker/parameters.yml.dist app/config/parameters.yml
docker-compose run php-fpm composer install
docker-compose run php-fpm bin/console doctrine:migrations:migrate --no-interaction
docker run --rm -ti -v $(pwd):/src:rw mkenney/npm:node-6.9-debian /usr/local/bin/npm install
docker run --rm -ti -v $(pwd):/src:rw mkenney/npm:node-6.9-debian /usr/local/bin/grunt
