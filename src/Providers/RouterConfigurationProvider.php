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
        $responseFactory = new \Laminas\Diactoros\ResponseFactory();
        $jsonstrategy = new \League\Route\Strategy\JsonStrategy($responseFactory);
        $jsonstrategy->setContainer($container);
        
        # Allows us, to output as on entire groups.
        $router->group('/api', function ($router) use ($container) {
            $router->map('GET', '/database', [\redcathedral\phpMySQLAdminrest\Controller\DatabaseController::class, 'listDatabasesAsJson']);
        })->setStrategy($jsonstrategy);

        # Register the router
        $container->add(\League\Route\Router::class, $router);
    }
}
