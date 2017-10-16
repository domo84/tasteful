start:
	cd src/public && php -S 0.0.0.0:7777

test:
	# phpunit --coverage-html coverage --whitelist src --bootstrap tests/requires.php tests
	phpunit --bootstrap tests/requires.php tests/Resources/ExamplesTest.php

clean:
	-rm storage/services.db
