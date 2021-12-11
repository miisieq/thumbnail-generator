#!make

DOCKER_IMAGE := "thumbnail-generator"

build:
	docker build --progress=plain --tag $(DOCKER_IMAGE) - < Dockerfile

install:
	docker container run --rm -v $(PWD):/app/ $(DOCKER_IMAGE) composer install

run:
	docker container run -it --rm -v $(PWD):/app/ $(DOCKER_IMAGE) php run.php

enter:
	docker container run -it --rm -v $(PWD):/app/ $(DOCKER_IMAGE) /bin/bash
