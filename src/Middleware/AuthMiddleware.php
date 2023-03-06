<?php

namespace redcathedral\phpMySQLAdminrest\Middleware;

use Dotenv\Dotenv;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Laminas\Diactoros\Response\RedirectResponse;
use redcathedral\phpMySQLAdminrest\Facades\JWTFacade;

class AuthMiddleware implements MiddlewareInterface
{

    private $dotenv;

    public function __construct(Dotenv $env)
    {
        $this->dotenv = $env;
    }

    /**
     * {@inheritdoc}
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        // determine authentication and/or authorization
        // ...
        if (isset($_SERVER["HTTP_AUTHORIZATION"])) {
            $payload = JWTFacade::verify($_SERVER["HTTP_AUTHORIZATION"]);

            // if user has auth, use the request handler to continue to the next
            // middleware and ultimately reach your route callable
            if ($payload->iss == JWTFacade::getIssuer()) {
                return $handler->handle($request);
            }
        }

        // if user does not have auth, possibly return a redirect response,
        // this will not continue to any further middleware and will never
        // reach your route callable
        return new RedirectResponse('/', 403);
    }
}
