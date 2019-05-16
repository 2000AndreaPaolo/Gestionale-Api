COMPOSE_PROJECT_NAME=gestionale-api

COMPOSE=docker-compose --project-name=$(COMPOSE_PROJECT_NAME) -f docker/docker-compose.yml
DEVCOMPOSE=$(COMPOSE) -f docker/docker-compose.dev.yml
PRODCOMPOSE=$(COMPOSE)

.PHONY: upd
upd:
	$(PRODCOMPOSE) up -d

.PHONY: up
up:
	$(PRODCOMPOSE) up

.PHONY: down
down:
	$(PRODCOMPOSE) down

.PHONY: devup
devup:
	$(DEVCOMPOSE) up

.PHONY: devupd
devupd:
	$(DEVCOMPOSE) up -d

.PHONY: devbuild
devbuild:
	$(DEVCOMPOSE) build

.PHONY: devdown
devdown:
	$(DEVCOMPOSE) down

.PHONY: devclear
devclear:
	$(DEVCOMPOSE) rm

.PHONY: db
db:
	docker exec -it gestionale-mdb sh -c 'mysql -ugestionale -pgestionale < /sql/schema.sql'

.PHONY: testdata
testdata: db
	docker exec -it gestionale-mdb sh -c 'mysql -ugestionale -pgestionale < /sql/test_data.sql'