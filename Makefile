#---VARIABLES---------------------------------#
#---COMPOSER-#
COMPOSER = composer
COMPOSER_INSTALL = $(COMPOSER) install
COMPOSER_UPDATE = $(COMPOSER) update
#------------#
#---SYMFONY--#
CONSOLE = php bin/console
#------------#
#---------------------------------------------#
## === 🆘  HELP ==================================================
help: ## Show this help.
	@echo "Symfony-And-Docker-Makefile"
	@echo "---------------------------"
	@echo "Usage: make [target]"
	@echo ""
	@echo "Targets:"
	@grep -E '(^[a-zA-Z0-9_-]+:.*?##.*$$)|(^##)' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}{printf "\033[32m%-30s\033[0m %s\n", $$1, $$2}' | sed -e 's/\[32m##/[33m/'
#---------------------------------------------#


# —— Check before Commit ✅‍️ —————————————————————————————————————————
check_commit: ## Run Validate composer - fix all style bug - PHPCPD - PhpStan.
	$(MAKE) composer-validate-deep
	$(MAKE) config-linter
	$(MAKE) templates-linter
	$(MAKE) container-linter
	$(MAKE) style-fixer
	$(MAKE) phpcpd
	$(MAKE) phpstan
	$(MAKE) phpunit

.PHONY: check_commit

# —— PHP Code Style Quality fixer ✅‍️ —————————————————————————————————————————
style-fixer:
	vendor/bin/ecs check --ansi --no-interaction --fix --config ecs.php
	vendor/bin/twig-cs-fixer lint --fix templates

.PHONY: style-fixer

## ===  PHP Static analyze ✨ ==============================================

phpcpd: ## Run PHPCPD.
	vendor/bin/phpcpd src

.PHONY: phpcpd

phpstan: ## Run PHPStan with level max.
	vendor/bin/phpstan analyse --xdebug --configuration=phpstan.neon

.PHONY: phpstan

## ===  PhpUnit ✨‍️ ==============================================

phpunit: ## Run all tests
	vendor/bin/phpunit --colors=always --testdox

.PHONY: phpunit

## === COMPOSER 📦 ==============================================
composer-install: ## Install composer dependencies.
	$(MAKE) composer-validate-deep
	$(COMPOSER_INSTALL)
.PHONY: composer-install

composer-update: ## Update composer dependencies.
	$(MAKE) composer-validate-deep
	$(COMPOSER_UPDATE)
.PHONY: composer-update

composer-validate: ## Validate composer.json file.
	$(COMPOSER) validate
.PHONY: composer-validate

composer-validate-deep: ## Validate composer.json and composer.lock files in strict mode.
	$(COMPOSER) validate --strict --check-lock
.PHONY: composer-validate-deep
#---------------------------------------------#

## === SYMFONY 🎛️  ===============================================
cc:
	$(CONSOLE) cache:clear
	rm -rf var/cache/*
	rm -rf var/log/*
.PHONY: cc

check-requirements:
	symfony check:requirements
.PHONY:check-requirements

check-security:
	$(CONSOLE)  check:security
.PHONY:check-security

container-linter:
	$(CONSOLE)  lint:container
.PHONY:container-linter

config-linter:
	$(CONSOLE)  lint:yaml config
.PHONY:config-linter

templates-linter:
	$(CONSOLE)  lint:twig templates
.PHONY:templates-linter

regenerate:
	$(CONSOLE)  doctrine:schema:valid --skip-sync
	$(CONSOLE)  make:entity --regenerate App
.PHONY:regenerate

regenerate-db:
	$(MAKE) cc
	$(MAKE) regenerate
	#$(CONSOLE)  d:s:u --force
	symfony console d:s:u --force
.PHONY:regenerate-db

mapping-valid:
	$(CONSOLE)  doctrine:schema:valid --skip-sync
.PHONY:mapping-valid

database:
	$(CONSOLE)  doctrine:database:drop --if-exists --force
	$(CONSOLE)  doctrine:database:create
	$(CONSOLE)  doctrine:schema:update --force
.PHONY:database

migration:
	$(CONSOLE)  doctrine:migrations:execute -n
.PHONY:migration

fixtures:
	$(MAKE) database
	$(MAKE) regenerate
	$(CONSOLE)  doctrine:fixtures:load -n
.PHONY:fixtures
#---------------------------------------------#
sf-me: ## Make symfony entity
	$(SYMFONY_CONSOLE) make:entity
.PHONY: sf-me

sf-mc: ## Make symfony controller
	$(SYMFONY_CONSOLE) make:controller
.PHONY: sf-mc

sf-mf: ## Make symfony Form
	$(SYMFONY_CONSOLE) make:form
.PHONY: sf-mf
#---------------------------------------------#
