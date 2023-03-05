# PHP MySQLAdmin Rest-API

![Code Coverage](https://raw.githubusercontent.com/newsworthy39/php-mysqladmin-rest/master/.github/badges/coverage.svg)

Full code-coverage rapport is available at https://github.com/newsworthy39/php-mysqladmin-rest/tree/main/coverage

The general idea, is to have an programatic solution, for dealing with long-running-services.
Instead of dealing with block-storage connected to containers, that needs to be moved around
use generalized long-running-services managed services that also provide administrative access
to their underlying systems.

## co-op administration
There is really no reason, to implement large-scale programmatic apis into a
piece of software, when we're perfectly capable of actually running seperate
programs quite well. By creating and implementing a _co-op_ program specifically for this administrative problem.

## Security and integration

We use JWT tokens to integrate with third-party authentication libraries.

## Dotenv-files

You should never store sensitive credentials in your code. Storing configuration in the environment is one of the tenets of a twelve-factor app. Anything that is likely to change between deployment environments – such as database credentials or credentials for 3rd party services – should be extracted from the code into environment variables.

Basically, a .env file is an easy way to load custom configuration variables that your application needs without having to modify .htaccess files or Apache/nginx virtual hosts. This means you won't have to edit any files outside the project, and all the environment variables are always set no matter how you run your project - Apache, Nginx, CLI, and even PHP's built-in webserver. It's WAY easier than all the other ways you know of to set environment variables, and you're going to love it!

NO editing virtual hosts in Apache or Nginx
NO adding php_value flags to .htaccess files
EASY portability and sharing of required ENV values
COMPATIBLE with PHP's built-in web server and CLI runner
PHP dotenv is a PHP version of the original Ruby dotenv.

DotEnv can be found on https://packagist.org/packages/vlucas/phpdotenv

## required entries in .env-file

The following variables are required in a .env-file, and will be used in `src/MySQLConfigurationBootableProvider.php`:

    DATABASE_HOST=localhost
    DATABASE_USER=someuser
    DATABASE_PASS=somepassword

# Build and installation

scripts, to helpout installating and running, can be found inside `devops`-folder:

    build.sh - builds components required to run

# Continouous integration

`main`-branch is protected and read-only. PRs are to be made towards main. 
Release-branches in the form of `tree/releases` will run complete integration-tests on integration-environment.

Tests reside reside in `tests`-directory.

## Upgrading

We follow semantic versioning, which means breaking changes may occur between major releases. Read about semantic versioning: https://semver.org/

In general semantic versioning, follows these rules strictly.

    Given a version number MAJOR.MINOR.PATCH, increment the:
    1. MAJOR version when you make incompatible API changes
    2. MINOR version when you add functionality in a backwards compatible manner
    3. PATCH version when you make backwards compatible bug fixes
    
    Additional labels for pre-release and build metadata are available as extensions to the MAJOR.MINOR.PATCH format.

## Code coverage

PHP Unit, quite well allows to mark functions with coverage and `phpunit.xml` is setup to warn about missing code-coverage. 

## Unit tests

This project uses phpunit to run unittests. The tests can be run:

    vendor/bin/phpunit

or by running

    composer test

## Coverage tests

composer scripts, has added `code-coverage` to its scripts-section, as: `"show-coverage": "cat .phpunit.cache/test-results.json | jq ."`
Run:
        
    composer show-coverage

to see the results of the latest test. Requires a `composer test` and `jq`.

## Integration testing on github

There is a github-workflow `.github/workflows/php.yml`

# Licenses

This software is released under [BSD-3-Clause](https://github.com/newsworthy39/php-mysqladmin-rest)

## Additional licenses

* thephpleague/route [MIT License](https://github.com/thephpleague/route)
* thephpleague/container [MIT License](https://github.com/thephpleague/container)
* vlucas/phpdotenv [BSD-3-Clause](https://github.com/vlucas/phpdotenv/)
* laminas/laminas-diactoros [BSD-3-Clause](https://github.com/laminas/laminas-diactoros)
* laminas/laminas-httphandlerrunner [BSD-3-Clause](https://github.com/laminas/laminas-httphandlerrunner)
* firebase/php-jwt [BSD-3-Clause](https://github.com/firebase/php-jwt)
* jaschilz/php-coverage-badger [MIT License](https://github.com/JASchilz/PHPCoverageBadge)