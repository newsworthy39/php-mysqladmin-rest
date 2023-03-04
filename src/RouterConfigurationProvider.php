<?php

namespace redcathedral\phpMySQLAdminrest;

use Laminas\Diactoros\ServerRequest;
use Psr\Http\Message\ServerRequestInterface;
use League\Container\ServiceProvider\AbstractServiceProvider;

class RouterConfigurationProvider extends AbstractServiceProvider
{
    public function provides(string $id): bool
    {
        $services = array(
            \League\Route\Router::class
        );

        return in_array($id, $services);
    }

    public function register(): void
    {
        $responseFactory = new \Laminas\Diactoros\ResponseFactory();
        $jsonstrategy = new \League\Route\Strategy\JsonStrategy($responseFactory);
        $router   = (new \League\Route\Router);

        // map a route
        $router->group('/api', function ($router) {
            $router->map('GET', '/test', function (ServerRequestInterface $request): array {
                return [
                    'title'   => 'My New Simple API',
                    'version' => 1,
                ];
            });
        })->setStrategy($jsonstrategy);

        $this->getContainer()->add(\League\Route\Router::class, $router);
    }
}
