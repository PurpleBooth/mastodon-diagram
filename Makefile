SHELL := bash
.ONESHELL:
.SHELLFLAGS := -eu -o pipefail -c
.DELETE_ON_ERROR:
MAKEFLAGS += --warn-undefined-variables
MAKEFLAGS += --no-builtin-rules
CORES := $(shell nproc)

.PHONY: show-help
## This help screen
show-help:
	@echo "$$(tput bold)Available rules:$$(tput sgr0)";echo;sed -ne"/^## /{h;s/.*//;:d" -e"H;n;s/^## //;td" -e"s/:.*//;G;s/\\n## /---/;s/\\n/ /g;p;}" ${MAKEFILE_LIST}|LC_ALL='C' sort -f|awk -F --- -v n=$$(tput cols) -v i=29 -v a="$$(tput setaf 6)" -v z="$$(tput sgr0)" '{printf"%s%*s%s ",a,-i,$$1,z;m=split($$2,w," ");l=n-i;for(j=1;j<=m;j++){l-=length(w[j])+1;if(l<= 0){l=n-i-length(w[j])-1;printf"\n%*s ",-i," ";}printf"%s ",w[j];}printf"\n";}'

.PHONY: lint
## Check for style and static analysis problems
lint:
	composer validate
	php-cs-fixer fix --allow-risky=yes --dry-run
	./vendor/bin/phpstan analyse

.PHONY: fix
## Automatically fix any problems possible
fix:
	php-cs-fixer fix --allow-risky=yes

.PHONY: test
## Run the tests
test:
	phpdbg -qrr ./vendor/bin/phpspec run --verbose
	phpdbg -qrr $(shell command -v infection) \
	       -j${CORES} \
	       --min-msi=100 \
	       --min-covered-msi=100 \
	       --test-framework=phpspec \
	       --coverage=coverage

.PHONY: serverless-deploy
## Deploy the application
serverless-deploy:
	npx serverless deploy

.PHONY: serverless-run
## Run the severless application
serverless-run:
	npx serverless invoke -f function


.PHONY: serverless-logs
## Get the logs of the application
serverless-logs:
	npx serverless logs -f function

