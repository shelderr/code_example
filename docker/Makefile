#!make
include .env
export $(shell sed 's/=.*//' .env)


init_cert:
	./init-letsencrypt.sh
	@echo "Cert ready!"

init:
	docker-compose pull
	docker-compose up -d --build

	@echo "System ready!"

update:
	docker-compose pull
	docker-compose up -d
	docker-compose restart

	@echo "System updated!"
