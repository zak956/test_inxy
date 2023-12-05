#! /bin/bash
cd ./laradock/

docker compose up --build --remove-orphans -d nginx postgres
docker compose run workspace composer install
docker compose run workspace cp -n .env.example .env
docker compose run workspace php artisan migrate:fresh --seed
