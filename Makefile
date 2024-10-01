init: docker-down-clean docker-build docker-up app-init
start: docker-down-clean docker-up
down: docker-down-clean
up: docker-up

env := ./.env
ifneq (,$(wildcard ${env}))
    include .env
    export
endif

docker_composer := docker compose  --env-file ${env} -p ${PROJECT_NAME}

docker_resolve:
	${docker_composer} config
docker-build:
	${docker_composer} build --build-arg UID="$(id -u)" --build-arg GROUP="$(id -g)"
docker-up:
	${docker_composer} up -d --remove-orphans
docker-down-clean:
	${docker_composer} down -v --remove-orphans
app-init: composer-update artisan-key-generate migrate-fresh-seed

composer-update:
	${docker_composer} exec app composer update
artisan-key-generate:
	${docker_composer} exec app php artisan key:generate
sh:
	${docker_composer} exec app sh
migrate:
	${docker_composer} exec app php artisan migrate:fresh
migrate-fresh-seed:
	${docker_composer} exec app php artisan migrate:fresh --seed
tinker:
	${docker_composer} exec app php artisan tinker
test:
	${docker_composer} exec app php artisan test
