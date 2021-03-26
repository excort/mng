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
fixtures:
	docker-compose exec php /bin/bash -c "php bin/console app:fixtures-load"
info:
	docker-compose ps

#composer
composer:
	docker-compose exec php composer install
dump-autoload:
	docker-compose exec php composer dump-autoload

