#!/usr/bin/env bash

docker-compose -f docker-compose-test.yml up -d && \
  docker-compose -f docker-compose-test.yml run php-cli bin/run_tests.sh
