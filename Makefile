LOCAL_DOCKER_EXEC =	docker exec -it php

up_app:
	docker compose up -d

up_build_app:
	docker compose up -d --build

stop_app:
	docker compose down

#unit_test: TODO
#	$(LOCAL_DOCKER_EXEC) vendor/bin/phpunit
#
#code_check: TODO
#	$(LOCAL_DOCKER_EXEC) composer check

migrations_up:
	$(LOCAL_DOCKER_EXEC) bin/console doctrine:migrations:migrate

composer_install:
	$(LOCAL_DOCKER_EXEC) composer install

init_app:
	make up_build_app \
	&& sleep 5 \
	&& make composer_install \
	&& sleep 5 \
	&& make migrations_up