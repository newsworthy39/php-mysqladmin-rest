<?php

namespace redcathedral\phpMySQLAdminrest\Controller;

use Laminas\Diactoros\Response\HtmlResponse;
use League\Route\Http\Exception\UnauthorizedException;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use redcathedral\phpMySQLAdminrest\Facades\JWTFacade;
use redcathedral\phpMySQLAdminrest\Implementations\HashSHA256;
use redcathedral\phpMySQLAdminrest\Traits\AuthenticationTrait;
use redcathedral\phpMySQLAdminrest\Strategy\AuthenticationStrategy;

/**
 * AuthenticationController is used, to issue JWT-tokens when no other authentication-backends are used.
 * It allows us, to use different backends through the AuthenticationStrategy, that you may
 * implement.
 *
 * @category Controller
 * @author   Newsworthy39 <newsworthy39@github.com>
 * @license  BSD-3 https://spdx.org/licenses/BSD-3-Clause.html
 */
class AuthenticationController
{
    use AuthenticationTrait;

    private $auth;

    public function __construct(AuthenticationStrategy $auth)
    {
        $this->auth = $auth;
    }

    public function authenticate(ServerRequestInterface $request, array $args): ResponseInterface
    {
        $token = $this->getBasicToken($request);
        if (!$token) {
            throw new UnauthorizedException();
        }

        list($username, $password) = explode(":", base64_decode($token));
        if ($this->auth->verify($username, HashSHA256::fromString($password))) {
            // Make a token
            $jwt = JWTFacade::encode(
                array(
                    "aud" => "me",
                    "uuid" => "64646464"
                )
            );

            $response = new HtmlResponse(json_encode(array('jwt' => $jwt)));
            return $response->withAddedHeader('content-type', 'application/json')
                ->withStatus(200);
        }

        throw new UnauthorizedException();
    }
}
