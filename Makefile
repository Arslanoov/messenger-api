init: docker-clear docker-up
up: docker-clear docker-up
check: lint cs psalm stan php-insights
fix: cs-fix
test: load-fixtures test-unit test-functional

docker-up:
	docker-compose up --build -d

docker-down:
	docker-compose down

docker-clear:
	docker-compose down --remove-orphans

install-deps:
	docker-compose run --rm messenger-php-cli composer install --ignore-platform-reqs

create-migration:
	docker-compose run --rm messenger-php-cli php bin/console do:mi:di
migrate:
	docker-compose run --rm messenger-php-cli php bin/console do:mi:mi

generate-oauth-keys:
	docker-compose run --rm messenger-php-cli mkdir -p var/oauth
	docker-compose run --rm messenger-php-cli openssl genrsa -out var/oauth/private.key 2048
	docker-compose run --rm messenger-php-cli openssl rsa -in var/oauth/private.key -pubout -out var/oauth/public.key
	docker-compose run --rm messenger-php-cli chmod -R 755 var/oauth

fix-permissions:
	docker-compose run --rm messenger-php-cli chmod -R 777 var/cache

clear-cache:
	docker-compose run --rm messenger-php-cli php bin/console cache:clear

generate-doc:
	docker-compose run --rm messenger-php-cli php bin/console api:doc:generate

lint:
	docker-compose run --rm messenger-php-cli composer lint

cs:
	docker-compose run --rm messenger-php-cli composer cs
cs-fix:
	docker-compose run --rm messenger-php-cli composer cs-fix
psalm:
	docker-compose run --rm messenger-php-cli composer psalm
stan:
	docker-compose run --rm messenger-php-cli composer stan
php-insights:
	docker-compose run --rm messenger-php-cli composer php-insights

test-all:
	docker-compose run --rm messenger-php-cli vendor/bin/phpunit --colors=always
load-fixtures:
	docker-compose run --rm messenger-php-cli php bin/console doctrine:fixtures:load --no-interaction
test-unit:
	docker-compose run --rm messenger-php-cli vendor/bin/phpunit --colors=always --testsuite=Unit
test-functional:
	docker-compose run --rm messenger-php-cli vendor/bin/phpunit --colors=always --testsuite=Functional

test-coverage:
	docker-compose run --rm messenger-php-cli chmod -R 755 var/cache
	docker-compose run --rm messenger-php-cli composer test-coverage

# Websocket
websocket-init: websocket-key websocket-install websocket-start

websocket-key:
	rm -f ./websocket/public.key
	cp ./app/var/oauth/public.key ./websocket/public.key

websocket-install:
	docker-compose exec websocket npm install

websocket-start:
	docker-compose exec websocket npm run start
