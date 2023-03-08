<?php

namespace redcathedral\phpMySQLAdminrest\Middleware;

use League\Route\Http\Exception\BadRequestException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use League\Route\Http\Exception\UnauthorizedException;
use redcathedral\phpMySQLAdminrest\Facades\JWTFacade;

/**
 * CloudTrail middleware. Logs requests in its form to a
 * central source.
 */
class CloudTrailMiddleware implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        return $handler->handle($request);
    }
}
