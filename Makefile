test:
	./phpunit --coverage-html coverage --whitelist src --bootstrap tests/autoload.php tests
