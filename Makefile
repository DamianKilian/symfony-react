start: up watch

rmps:
	docker rm -vf $$(docker ps -aq)

up:
	docker compose up -d

watch:
	docker compose run npm npm run watch

build:
	docker compose up -d --build
