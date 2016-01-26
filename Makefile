DB := app_download_dev

fake: migrate
	./vendor/bin/clips fake

migrate:
	@mysql -u root -e "drop database if exists ${DB}"
	@mysql -u root -e "create database ${DB}"
	@./vendor/bin/clips phinx migrate

test:
	@phpunit

c:
	@mysql -u root "${DB}"

widget:
	./vendor/bin/clips generate widget