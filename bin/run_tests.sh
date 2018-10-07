#!/bin/sh

if [ "$TRAVIS" = true ]; then
    phpenv config-rm xdebug.ini
fi
vendor/bin/phpspec run --no-interaction
phpspec_exit_code=$?

bin/phpunit
phpunit_exit_code=$?

vendor/bin/phpcs --ignore=src/Elewant/AppBundle/Command/RebuildHerdProjectionCommand.php
phpcs_exit_code=$?

vendor/bin/phpstan analyse --configuration phpstan.neon --level 7 --no-progress src
phpstan_exit_code=$?

# Always run all the test tools, but exit with
# a non-zero exit code on failures for Travis.
exit $(($phpspec_exit_code + $phpunit_exit_code + $phpcs_exit_code + $phpstan_exit_code))
