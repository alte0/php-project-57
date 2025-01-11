start:
	php artisan serve --host 0.0.0.0

start-frontend:
	npm run dev

setup: install copyEnv
	php artisan key:gen --ansi
	touch database/database.sqlite
	php artisan migrate
	php artisan db:seed
	npm ci
	npm run build
	make ide-helper

install:
	composer install

workflow:install
	php artisan key:gen --ansi
	php artisan migrate
	php artisan db:seed

copyEnv:
	cp -n .env.example .env

install-prod:install
	cp -n .env.example .env
	php artisan key:gen --ansi

watch:
	npm run watch

migrate:
	php artisan migrate

console:
	php artisan tinker

log:
	tail -f storage/logs/laravel.log

test:
	php artisan test

#test-coverage:
	#XDEBUG_MODE=coverage php artisan test --coverage-clover build/logs/clover.xml

lint:
	composer phpcs
#lint:
	#composer exec --verbose phpcs -- --standard=PSR12 src public

lint-fix:
	composer phpcbf

test-coverage:
	XDEBUG_MODE=coverage composer exec --verbose phpunit tests -- --coverage-clover build/logs/clover.xml

compose:
	docker-compose up

compose-test:
	docker-compose run web make test

compose-bash:
	docker-compose run web bash

compose-setup: compose-build
	docker-compose run web make setup

compose-build:
	docker-compose build

compose-db:
	docker-compose exec db psql -U postgres

compose-down:
	docker-compose down -v

ide-helper:
	php artisan ide-helper:eloquent
	php artisan ide-helper:gen
	php artisan ide-helper:meta
	php artisan ide-helper:mod -n
