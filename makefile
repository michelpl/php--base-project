# defines variables
#include Make.config

install:
	cp src/.env.example src/.env
	docker-compose up -d 
	docker-compose exec webapi composer update -vvv
	docker-compose exec webapi php artisan migrate:fresh

run:
	docker-compose up -d 

stop:
	docker-compose down

test:
	docker-compose exec webapi php artisan test

showlogs:
	echo "Showing logs...... \n " && tail -f src/storage/logs/laravel.log

permissions:
	sudo find src/ -type d -exec chmod 775 {} \;
	sudo find src/ -type f -exec chmod 664 {} \;
	sudo chown -R www-data:${USER} src
