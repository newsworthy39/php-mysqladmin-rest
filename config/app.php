<?php

namespace redcathedral\phpMySQLAdminrest;


use redcathedral\phpMySQLAdminrest\Providers\MySQLConfigurationBootableProvider;
use redcathedral\phpMySQLAdminrest\Providers\RouterConfigurationProvider;
use redcathedral\phpMySQLAdminrest\Providers\JWTAuthenticateProvider;
use redcathedral\phpMySQLAdminrest\Controller\DatabaseController;
use redcathedral\phpMySQLAdminrest\Implementations\HashSHA256;
use Psr\Http\Message\ServerRequestInterface;
use Dotenv\Dotenv;
use mysqli;

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
        //$container->addServiceProvider(new FileAuthenticationProvider($dotenv));

        # These are classes, required to our application.
        $container->add(\redcathedral\phpMySQLAdminrest\MySQLAdmin::class)->addArgument(mysqli::class);
        $container->add(\redcathedral\phpMySQLAdminrest\Controller\DatabaseController::class)->addArgument(\redcathedral\phpMySQLAdminrest\MySQLAdmin::class);
        $container->add(\redcathedral\phpMySQLAdminrest\Middleware\JWTAuthMiddleware::class);

        // TBD: This could come from an in-memory authentication-provider.
        $container->addShared(\redcathedral\phpMySQLAdminrest\Strategy\InMemoryAuthenticationStrategy::class, function () {
            $auth = new \redcathedral\phpMySQLAdminrest\Strategy\InMemoryAuthenticationStrategy();
            $auth->addUser("admin", HashSHA256::fromHash("25f43b1486ad95a1398e3eeb3d83bc4010015fcc9bedb35b432e00298d5021f7")); 
            return $auth;
        });
        $container->add(\redcathedral\phpMySQLAdminrest\Controller\AuthenticationController::class)->addArgument(\redcathedral\phpMySQLAdminrest\Strategy\InMemoryAuthenticationStrategy::class);
    }

    return $container;
}

/**
 * @brief Dispatch(ServerRequestInterface)
 * Dispatches a ServerRequest into a Router.
 */
function Dispatch(ServerRequestInterface $request)
{
    // Exceptions are caught in the strategy middleware interfaces.
    return App()->get(\League\Route\Router::class)->dispatch($request);
}
