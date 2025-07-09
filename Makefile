# StudiosDB Enterprise - Makefile
.PHONY: help install dev test build deploy clean

# Variables
PHP := php
COMPOSER := composer
NPM := npm
ARTISAN := $(PHP) artisan

## help: Show this help message
help:
	@echo "StudiosDB Enterprise - Command Center"
	@echo "===================================="
	@grep -E '^##' Makefile | sed -e 's/## //'

## dev: Start development servers
dev:
	@trap 'kill %1' INT; \
	$(ARTISAN) serve & \
	$(NPM) run dev

## test: Run all tests
test:
	@$(ARTISAN) test --parallel

## lint: Run linters
lint:
	@./vendor/bin/pint --test 2>/dev/null || echo "Pint not installed"
	@$(NPM) run lint 2>/dev/null || echo "No lint script defined"

## clear: Clear all caches
clear:
	@$(ARTISAN) cache:clear
	@$(ARTISAN) config:clear
	@$(ARTISAN) route:clear
	@$(ARTISAN) view:clear

## pr: Create pull request (requires gh CLI)
pr:
	@gh pr create --fill 2>/dev/null || echo "Install GitHub CLI: https://cli.github.com"
