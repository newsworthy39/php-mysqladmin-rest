<?php

namespace redcathedral\phpMySQLAdminrest\Providers;

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
        $router->middleware(new \redcathedral\phpMySQLAdminrest\Middleware\CloudTrailMiddleware);

        # REST-api for database-handling, using JWTs.
        $router->group('/api', function ($router) {
            $router->map('GET', '/database', [\redcathedral\phpMySQLAdminrest\Controller\DatabaseController::class, 'listDatabases']);
            $router->map('POST', '/database', [\redcathedral\phpMySQLAdminrest\Controller\DatabaseController::class, 'createDatabase'])->middleware(new \redcathedral\phpMySQLAdminrest\Middleware\JSONOnlyMiddleware);
            $router->map('PUT', '/database/{name}', [\redcathedral\phpMySQLAdminrest\Controller\DatabaseController::class, 'updateDatabase'])->middleware(new \redcathedral\phpMySQLAdminrest\Middleware\JSONOnlyMiddleware);
            $router->map('DELETE', '/database/{name}', [\redcathedral\phpMySQLAdminrest\Controller\DatabaseController::class, 'deleteDatabase'])->middleware(new \redcathedral\phpMySQLAdminrest\Middleware\JSONOnlyMiddleware);
        })->middleware($container->get(\redcathedral\phpMySQLAdminrest\Middleware\JWTAuthMiddleware::class));

        # Allows us, to issue JWT, using an inmemoryauthenticationprovider.
        $router->group('/api', function ($router) {
            $router->map('GET', '/authenticate', [\redcathedral\phpMySQLAdminrest\Controller\AuthenticationController::class, 'authenticate']);
        });

        # Register the router
        $container->add(\League\Route\Router::class, $router);
    }
}
