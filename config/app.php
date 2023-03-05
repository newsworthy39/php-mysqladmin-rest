<?php

namespace redcathedral\phpMySQLAdminrest;

use Dotenv\Dotenv;
use redcathedral\phpMySQLAdminrest\Providers\MySQLConfigurationBootableProvider;
use redcathedral\phpMySQLAdminrest\Providers\RouterConfigurationProvider;
use mysqli;
use redcathedral\phpMySQLAdminrest\Controller\DatabaseController;

function App(): \League\Container\Container
{
    static $container;
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

        # These are classes, required to our application.
        $container->add(\redcathedral\phpMySQLAdminrest\MySQLAdmin::class)->addArgument(mysqli::class);
        $container->add(\redcathedral\phpMySQLAdminrest\Controller\DatabaseController::class)->addArgument(\redcathedral\phpMySQLAdminrest\MySQLAdmin::class);
    }

    return $container;
}
