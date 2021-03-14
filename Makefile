init: docker-clear docker-up
up: docker-clear docker-up

docker-up:
	docker-compose up --build -d

docker-down:
	docker-compose down

docker-clear:
	docker-compose down --remove-orphans
