LOCAL_DOCKER_EXEC =	docker exec -it php

up_app:
	docker compose up -d

up_build_app:
	docker compose up -d --build

stop_app:
	docker compose down

unit_test:
	$(LOCAL_DOCKER_EXEC) vendor/bin/phpunit

code_check:
	$(LOCAL_DOCKER_EXEC) composer check

init_app:
	echo 1;