install: ; composer install

test: ; php ./vendor/bin/phpunit

composer: ; docker-compose exec php composer install

up: ; docker-compose up -d --build

start: up composer