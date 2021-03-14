init: docker-clear docker-up
up: docker-clear docker-up
check: lint cs
fix: cs-fix

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
