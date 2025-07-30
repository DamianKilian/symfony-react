start: up watch

rmps:
	docker rm -vf $$(docker ps -aq)

up:
	docker compose up -d

watch:
	docker compose run npm npm run watch

build:
	docker compose up -d --build

app-setup:
	docker compose run composer install
	docker compose run console doctrine:migrations:migrate
	docker compose run console doctrine:fixtures:load
