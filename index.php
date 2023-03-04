<?php

require __DIR__.'/vendor/autoload.php';

use function redcathedral\phpMySQLAdminrest\App;

$router = App()->get(\League\Route\Router::class);

$request = \Laminas\Diactoros\ServerRequestFactory::fromGlobals(
    $_SERVER, $_GET, $_POST, $_COOKIE, $_FILES
);

$response = $router->dispatch($request);

// send the response to the browser
(new \Laminas\HttpHandlerRunner\Emitter\SapiEmitter)->emit($response);