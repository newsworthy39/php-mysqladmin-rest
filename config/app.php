<?php

namespace redcathedral\phpMySQLAdminrest;

use Dotenv\Dotenv;
use redcathedral\phpMySQLAdminrest\Providers\MySQLConfigurationBootableProvider;
use redcathedral\phpMySQLAdminrest\Providers\RouterConfigurationProvider;
use redcathedral\phpMySQLAdminrest\Providers\JWTAuthenticateProvider;
use redcathedral\phpMySQLAdminrest\Controller\DatabaseController;
use mysqli;
use Psr\Http\Message\ServerRequestInterface;

function App(): \League\Container\Container
{
    static $container; // late init

    if ($container == null) {

        $container = new \League\Container\Container;

        $dotenv = Dotenv::createImmutable(dirname(__DIR__, 1));

        $dotenv->load();

        /**
         * Bootable configuration-providers, allows us to register
         * functions and types into our container before run-time.
         * This is particularly handy, where code is dependant on its runtime-settings.
         */
        $container->addServiceProvider(new MySQLConfigurationBootableProvider($dotenv));
        $container->addServiceProvider(new RouterConfigurationProvider);
        $container->addServiceProvider(new JWTAuthenticateProvider($dotenv));

        # These are classes, required to our application.
        $container->add(\redcathedral\phpMySQLAdminrest\MySQLAdmin::class)->addArgument(mysqli::class);
        $container->add(\redcathedral\phpMySQLAdminrest\Controller\DatabaseController::class)->addArgument(\redcathedral\phpMySQLAdminrest\MySQLAdmin::class);
        $container->add(\redcathedral\phpMySQLAdminrest\Middleware\JWTAuthMiddleware::class);
    }

    return $container;
}

/**
 * @brief Dispatch(ServerRequestInterface)
 * Dispatches a ServerRequest into a Router.
 */
function Dispatch(ServerRequestInterface $request)
{
    try {
        return (App()->get(\League\Route\Router::class))->dispatch($request);
    } catch (\League\Container\Exception\NotFoundException $ex) {
    }
}
