SHELL := bash
.ONESHELL:
# .SHELLFLAGS := -eu -o pipefail -c
.SHELLFLAGS := -e -c
.DELETE_ON_ERROR:
MAKEFLAGS += --warn-undefined-variables
MAKEFLAGS += --no-builtin-rules
CORES := $(shell nproc)
MINUMUM_CODE_COVERAGE := 100
TEMP_DIR := $(shell mktemp -d)

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
	( cd frontend && npm run-script lint )

.PHONY: fix
## Automatically fix any problems possible
fix: fix-php fix-frontend

.PHONY: fix-php
## Automatically fix any problems possible
fix-php:
	php-cs-fixer fix --allow-risky=yes

.PHONY: fix-frontend
## Automatically fix any problems possible
fix-frontend:
	( cd frontend && npm run-script lint -- --fix )

.PHONY: build
# Build the application
build: build-frontend

.PHONY: build-frontend
## Build the frontend
build-frontend:
	( cd frontend && npm run-script build:browser:prod )

.PHONY: clean
## Delete built objects
clean: clean-frontend clean-php

.PHONY: clean
## Delete built objects
clean-frontend:
	rm -rf frontend/dist

.PHONY: clean-php
## Delete built objects
clean-php:
	rm -rf .serverless coverage node_modules .php_cs.cache infection.log

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
test-frontend: build-frontend
	( cd frontend && npm run-script test -- --watch=false )
	cp frontend/src/aws-exports.js "${TEMP_DIR}/aws-exports.js" \
	    && frontend/scripts/e2e-with-mocks \
	    && cp "${TEMP_DIR}/aws-exports.js" frontend/src/aws-exports.js

.PHONY: deploy
## Deploy the frontend
deploy: deploy-frontend deploy-php

.PHONY: deploy-frontend
## Deploy the frontend
deploy-frontend:
	## Cowardly bailing if we're in mock mode
	bash -c '! grep -q "\"aws_user_files_s3_dangerously_connect_to_http_endpoint_for_testing\": true" frontend/src/aws-exports.js'
	( cd frontend && amplify publish )

.PHONY: deploy-php
## Deploy the application
deploy-php:
	npx serverless deploy && ( make run-php || ( make logs-php && exit 1 ) )

.PHONY: run-php
## Run the severless application
run-php:
	npx serverless invoke -f poll

.PHONY: logs-php
## Get the logs of the application
logs-php:
	npx serverless logs -f poll

.PHONY: remove-deploy-php
## Get the logs of the application
remove-deploy-php:
	npx serverless remove

