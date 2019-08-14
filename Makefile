validate-deps: composer.json composer.lock
	composer validate

vendor: composer.json composer.lock
	composer install -n --prefer-dist

phpcs:
	-vendor/bin/phpcs -p --colors

phpmd:
	-vendor/bin/phpmd src/ text phpmd.xml.dist > storage/mess-detector.txt

phpstan:
	-vendor/bin/phpstan analyse src/ -l max --no-interaction --no-progress --error-format=checkstyle > storage/checkstyle.xml

code-standards: phpcs phpmd phpstan

coverage-test:
	vendor/bin/phpunit --config phpunit.xml.dist
