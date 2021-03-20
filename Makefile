init: docker-clear docker-up
up: docker-clear docker-up
check: lint cs psalm stan php-insights
fix: cs-fix
test: test-unit

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
test-unit:
	docker-compose run --rm messenger-php-cli vendor/bin/phpunit --colors=always --testsuite=Unit
test-functional:
	docker-compose run --rm messenger-php-cli vendor/bin/phpunit --colors=always --testsuite=Functional
