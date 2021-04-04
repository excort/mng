# Test app for learning MongoDb 

for developing you just need copy .env.dist to .env and start 
in cmd line next command:

- docker-compose down -v --remove-orphans
- docker-compose up -d --build
- docker-compose exec php composer install
- docker-compose exec php /bin/bash -c "php bin/console app:fixtures-load"

After the command finished you can see credentials for users in:

var/log/user-[now_date].log

For starting work just read the api doc:
http://127.0.0.1:8890/

If you want to do test of the system just start next command:
- docker-compose exec php /bin/bash -c "php bin/phpunit"

If you want to check cod for style (PSR-12), just ran this:
- docker-compose exec php /bin/bash -c "php vendor/bin/phpcs --standard=PSR12 src"
