# PHP MySQLAdmin Rest 

The general idea, is to have an programatic solution, for dealing with long-running-services.
Instead of dealing with block-storage connected to containers, that needs to be moved around
use generalized long-running-services managed services that also provide administrative access
to their underlying systems.

## co-op administration
There is really no reason, to implement large-scale programmatic apis into a
piece of software, when we're perfectly capable of actually running seperate
programs quite well. By creating and implementing a _co-op_ program specifically for this administrative problem.

# Continouous integration

Tests reside reside in `tests`-directory.

## Code coverage

PHP Unit, quite well allows to mark functions with coverage and `phpunit.xml` is setup to warn about missing code-coverage. 

## Unit tests

This project uses phpunit to run unittests. The tests can be run:

    vendor/bin/phpunit

or by running

    composer test

## Integration testing on github

There is a github-workflow `.github/workflows/php.yml`