init: docker-clear docker-up
up: docker-clear docker-up
check: lint cs psalm stan
fix: cs-fix
test: test-unit

docker-up:
	docker-compose up --build -d

docker-down:
	docker-compose down

docker-clear:
	docker-compose down --remove-orphans

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

test-all:
	docker-compose run --rm messenger-php-cli vendor/bin/phpunit --colors=always
test-unit:
	docker-compose run --rm messenger-php-cli vendor/bin/phpunit --colors=always --testsuite=Unit
test-functional:
	docker-compose run --rm messenger-php-cli vendor/bin/phpunit --colors=always --testsuite=Functional
