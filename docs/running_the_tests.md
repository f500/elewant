Running the tests
=====================

### PHPSPEC

The core domain is tested with a BDD tool called [phpspec](http://www.phpspec.net/). You can find the specifications
in the `/spec` folder, they 'describe' what the tested code should be doing, and can be run to verify that that is
actually the case.

    # running the phpspec test suite:
    vendor/bin/phpspec run
    vendor/bin/phpspec run spec/Path/To/A/Folder/
    vendor/bin/phpspec run spec/Path/To/A/Specific/File.php

### PHPUNIT

Then there are some tests that try to run the application through the framework, after bootstrapping. This helps to
verify that all the configuration/wiring is in order. Those tests are written in [phpunit](https://phpunit.de/), but
using the Symfony WebTestCase as a base. The tests are located in the `/test` folder.

    # running the phpunit test suite:
    vendor/bin/phpunit
    vendor/bin/phpunit tests/Path/To/A/Folder/
    vendor/bin/phpunit tests/Path/To/A/Specific/File.php

### PHPCS

Additionally, we use the [PHPCS](https://github.com/squizlabs/PHP_CodeSniffer/wiki) code style analysis tool to verify
that we do not violate the coding standards. There is a configuration file in the root called phpcs.xml, but it's
basically PSR2 + some newer PHP7+ rules.

    # running phpcs
    vendor/bin/phpcs
    vendor/bin/phpcs src/Path/To/A/Folder/
    vendor/bin/phpcs src/Path/To/A/Specific/File.php

### PHPSTAN

Additionally, we use the [PHPStan](https://github.com/phpstan/phpstan) static analysis tool to verify that we do not
have any detectable PHP errors.

    # running phpstan
    vendor/bin/phpstan analyse --configuration phpstan.neon --level max --no-progress src

### Humbug

Additionally, we use the [Humbug](https://github.com/humbug/humbug) mutation testing tool to verify that the unit
tests are covering code mutations. Thus preventing any accidental code change that is not caught by a PHPUnit Test.

    # running *bah!* Humbug
    TEST_SUITE=humbug bin/run_tests

### Debugging

For all the test suites (or any other command) you have the option to run the debugger form the commandline:

    # phpd is an alias for "php+start_the_debugger"
    phpd vendor/bin/phpspec run

### Running the entire suite

For your convenience, there is also a file that runs all suites back to back. That's also what Travis does.

    # running all the tests
    bin/run_tests
