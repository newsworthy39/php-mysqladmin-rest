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
 * JSONOnly middleware, ensures that Content-Types are correct
 * when sent, but does not actually verify it.
 */
class JSONOnlyMiddleware implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $acceptHeader = $request->getHeader('content-type');
        if (count($acceptHeader) < 1) {
            throw new BadRequestException("Bad request. Missing header Content-Type: application/json.");
        }
        foreach ($acceptHeader as $header) {
            if ($header != "application/json") {
                throw new BadRequestException("Bad request. You must a request " .
                    "with content_type: application/json or */*.");
            }
        }

        return $handler->handle($request);
    }
}
