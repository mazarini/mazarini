# Makefile

all: validate phpmd

validate: composer phpcs container twig yaml phpstan test

#################################################################
# install
#################################################################
install:
	composer install

#################################################################
# composer
#################################################################
composer:
	composer validate --strict

#################################################################
# container
#################################################################
container:
	bin/console lint:container

#################################################################
# php-cs-fixer
#################################################################
phpcs:
	php-cs-fixer fix --config config/tool/php-cs-fixer/.php-cs-fixer.dist.php

#################################################################
# phpmd
#################################################################
phpmd:
	phpmd src,lib,tests text config/tool/phpmd/rulesets.xml

#################################################################
# phpstan
#################################################################
phpstan:
	phpstan -cconfig/tool/phpstan/phpstan.neon.dist

#################################################################
# phpunit
#################################################################
cover: cover-text

test:
	bin/phpunit --cache-result-file var/cache/phpunit.result.cache --configuration config/tool/phpunit/phpunit.xml.dist

cover-html:
	XDEBUG_MODE=coverage bin/phpunit --cache-result-file var/cache/phpunit.result.cache --configuration config/tool/phpunit/phpunit.xml.dist --coverage-html var/cover

cover-text:
	XDEBUG_MODE=coverage bin/phpunit --cache-result-file var/cache/phpunit.result.cache --configuration config/tool/phpunit/phpunit.xml.dist --coverage-text

#################################################################
# twig
#################################################################
twig:
	bin/console lint:twig templates lib

#################################################################
# twig
#################################################################
yaml:
	bin/console lint:yaml config config/tool/phpstan/phpstan.neon.dist .github/workflows
    
#################################################################
# Database
#################################################################

init:
	mkdir -p var/data
	bin/console doctrine:database:drop --env=dev --force
	bin/console doctrine:database:drop --env=test --force
	bin/console doctrine:database:create --env=dev
	bin/console doctrine:database:create --env=test
	bin/console doctrine:migrations:migrate --no-interaction --env=dev
	bin/console doctrine:migrations:migrate --no-interaction --env=test
