prune:
	docker-compose down -v --remove-orphans
build:
	docker-compose up -d --build
up:
	docker-compose up -d
down:
	docker-compose down
bash:
	docker-compose exec php /bin/bash
