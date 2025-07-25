start: up

rmps:
	docker rm -vf $$(docker ps -aq)

up:
	docker compose up -d

build:
	docker compose up -d --build
