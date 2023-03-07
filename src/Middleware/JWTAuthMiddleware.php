<?php

namespace redcathedral\phpMySQLAdminrest\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use League\Route\Http\Exception\UnauthorizedException;
use redcathedral\phpMySQLAdminrest\Facades\JWTFacade;
use redcathedral\phpMySQLAdminrest\Traits\AuthenticationTrait;

class JWTAuthMiddleware implements MiddlewareInterface
{
    use AuthenticationTrait;

    /**
     * {@inheritdoc}
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        // determine authentication and/or authorization
        // ...
        $bearerToken = $this->getBearerToken();

        if ($bearerToken) {
            $payload = JWTFacade::verify($bearerToken); 

            
            // if user has auth, use the request handler to continue to the next
            // middleware and ultimately reach your route callable.
            // Criteria:
            // Check expiry of field, if missing 
            if (time() < $payload->exp && $payload->iss == JWTFacade::getIssuer()) {
                return $handler->handle($request);
            }
        }

        // if user does not have auth, possibly return a redirect response,
        // this will not continue to any further middleware and will never
        // reach your route callable
        throw new UnauthorizedException();
    }
}
