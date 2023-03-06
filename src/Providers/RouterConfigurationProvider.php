<?php

namespace redcathedral\phpMySQLAdminrest\Providers;

use Laminas\Diactoros\ServerRequest;
use Psr\Http\Message\ServerRequestInterface;
use League\Container\ServiceProvider\AbstractServiceProvider;

class RouterConfigurationProvider extends AbstractServiceProvider
{
    public function provides(string $id): bool
    {
        $services = array(
            \League\Route\Router::class,
        );

        return in_array($id, $services);
    }

    public function register(): void
    {
        $container = $this->getContainer();

        # Allows router, to find objects within the container, while outputting as json.
        $router   = (new \League\Route\Router);

        # Set Json ouput and delegate object creation to a container.
        $responseFactory = new \Laminas\Diactoros\ResponseFactory();
        $jsonstrategy = new \League\Route\Strategy\JsonStrategy($responseFactory);
        $jsonstrategy->setContainer($container);
        $router->setStrategy($jsonstrategy);
        
        # Allows us, to output as on entire groups.
        $router->group('/api', function ($router) {
            $router->map('GET', '/database', [\redcathedral\phpMySQLAdminrest\Controller\DatabaseController::class, 'listDatabasesAsJson']);
        })->middleware($container->get(\redcathedral\phpMySQLAdminrest\Middleware\JWTAuthMiddleware::class));;

        # Allows us, to use sign-fuctions, etc
        $router->group('/api', function($router) {
            $router->map('GET', '/authenticate', [\redcathedral\phpMySQLAdminrest\Controller\AuthenticationController::class, 'authenticate']);
        });

        # Register the router
        $container->add(\League\Route\Router::class, $router);
    }
}
