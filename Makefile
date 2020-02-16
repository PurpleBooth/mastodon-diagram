SHELL := bash
.ONESHELL:
.SHELLFLAGS := -eu -o pipefail -c
.DELETE_ON_ERROR:
MAKEFLAGS += --warn-undefined-variables
MAKEFLAGS += --no-builtin-rules
CORES := $(shell nproc)
MINUMUM_CODE_COVERAGE := 100

.PHONY: show-help
## This help screen
show-help:
	@echo "$$(tput bold)Available rules:$$(tput sgr0)";echo;sed -ne"/^## /{h;s/.*//;:d" -e"H;n;s/^## //;td" -e"s/:.*//;G;s/\\n## /---/;s/\\n/ /g;p;}" ${MAKEFILE_LIST}|LC_ALL='C' sort -f|awk -F --- -v n=$$(tput cols) -v i=29 -v a="$$(tput setaf 6)" -v z="$$(tput sgr0)" '{printf"%s%*s%s ",a,-i,$$1,z;m=split($$2,w," ");l=n-i;for(j=1;j<=m;j++){l-=length(w[j])+1;if(l<= 0){l=n-i-length(w[j])-1;printf"\n%*s ",-i," ";}printf"%s ",w[j];}printf"\n";}'

.PHONY: lint
## Check for style and static analysis problems
lint: lint-php lint-frontend

.PHONY: lint-php
## Check for style and static analysis problems
lint-php:
	composer validate
	php-cs-fixer fix --allow-risky=yes --dry-run
	./vendor/bin/phpstan analyse

.PHONY: lint-frontend
## Check for style and static analysis problems
lint-frontend:
	( cd frontend && ng lint )

.PHONY: fix
## Automatically fix any problems possible
fix:
	php-cs-fixer fix --allow-risky=yes
	( cd frontend && ng lint --fix )

.PHONY: test
## Run the tests
test: test-php test-frontend

.PHONY: test-php
## Run the tests php
test-php:
	phpdbg -qrr ./vendor/bin/phpspec run --verbose
	phpdbg -qrr $(shell command -v infection) \
	       -j${CORES} \
	       --min-msi=${MINUMUM_CODE_COVERAGE} \
	       --min-covered-msi=${MINUMUM_CODE_COVERAGE} \
	       --test-framework=phpspec \
	       --coverage=coverage || ( cat infection.log && exit 1 )

.PHONY: test-frontend
## Run the tests frontend
test-frontend:
	( cd frontend && ng test --watch=false )
	( cd frontend && ng e2e )

.PHONY: deploy
## Deploy the frontend
deploy: amplify-deploy serverless-deploy

.PHONY: amplify-deploy
## Deploy the frontend
amplify-deploy:
	( cd frontend && amplify publish )

.PHONY: serverless-deploy
## Deploy the application
serverless-deploy:
	npx serverless deploy && ( make serverless-run || ( make serverless-logs && exit 1 ) )
	( cd frontend && amplify publish )

.PHONY: serverless-run
## Run the severless application
serverless-run:
	npx serverless invoke -f poll

.PHONY: serverless-logs
## Get the logs of the application
serverless-logs:
	npx serverless logs -f poll

.PHONY: serverless-remove
## Get the logs of the application
serverless-remove:
	npx serverless remove

