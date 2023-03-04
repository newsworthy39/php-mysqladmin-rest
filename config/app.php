<?php

namespace redcathedral\phpMySQLAdminrest;

use redcathedral\phpMySQLAdminrest\MySQLConfigurationBootableProvider;
use mysqli;

function App(): \League\Container\Container
{
    static $container;
    if ($container == null) {
        
        $container = new \League\Container\Container;

        /**
         * Bootable configuration-providers, allows us to register
         * functions and types into our container before run-time.
         * This is particularly handy, where code is dependant on its runtime-settings.
         */
        $container->addServiceProvider(new MySQLConfigurationBootableProvider);

        # These are classes, required to our application.
        $container->add(\redcathedral\phpMySQLAdminrest\MySQLAdmin::class)->addArgument(mysqli::class);
    }
    return $container;
}
