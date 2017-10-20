start:
	cd public && php -S 0.0.0.0:7777

test: clean
	# phpunit --coverage-html coverage --whitelist src --bootstrap tests/requires.php tests
	phpunit --bootstrap tests/requires.php tests

clean:
	-rm services.db
