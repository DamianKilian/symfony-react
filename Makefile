start: up watch

rmps:
	docker rm -vf $$(docker ps -aq)

up:
	docker compose up -d

watch:
	npm run watch

build:
	docker compose up -d --build
