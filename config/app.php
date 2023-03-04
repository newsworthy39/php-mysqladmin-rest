<?php

namespace redcathedral\phpmysqladminrest;

use mysqli;
use League\Container\Container;

static $container; // Our PSR-compatible container.
function App(): Container
{
    static $container;
    if ($container == null) {
        $container = new Container();
    }

    $container->add(mysqli::class)->addArgument('localhost')->addArgument('mysqladmin')->addArgument('superadmin');
    $container->add(\redcathedral\phpmysqladminrest\mysqladmin::class)->addArgument(mysqli::class);

    return $container;
}
