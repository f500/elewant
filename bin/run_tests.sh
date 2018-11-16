#!/bin/sh

if [ "$TRAVIS" = true ]; then
    phpenv config-rm xdebug.ini
fi

echo ">>>>>>>>>>>>> CLEAR CACHE  <<<<<<<<<<<<<"
bin/console cache:clear --env=test

echo ">>>>>>>>>> PHPSPEC <<<<<<<<<<"
vendor/bin/phpspec run --no-interaction
phpspec_exit_code=$?

echo ">>>>>>>>>> PHPCS <<<<<<<<<<"
vendor/bin/phpcs
phpcs_exit_code=$?

echo ">>>>>>>>>> PHPSSTAN <<<<<<<<<<"
vendor/bin/phpstan analyse --configuration phpstan.neon --level max --no-progress src
phpstan_exit_code=$?

echo ">>>>>>>>>> PHPUNIT <<<<<<<<<<"
bin/phpunit
phpunit_exit_code=$?

# Always run all the test tools, but exit with
# a non-zero exit code on failures for Travis.
exit $(($phpspec_exit_code + $phpunit_exit_code + $phpcs_exit_code + $phpstan_exit_code))
