<?php

namespace redcathedral\phpMySQLAdminrest\Controller;

use Laminas\Diactoros\Response\HtmlResponse;
use League\Route\Http\Exception\UnauthorizedException;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use redcathedral\phpMySQLAdminrest\Facades\JWTFacade;
use redcathedral\phpMySQLAdminrest\Traits\AuthenticationTrait;

class AuthenticationController
{
    use AuthenticationTrait;

    public function authenticate(ServerRequestInterface $request, array $args): ResponseInterface
    {
        $token = $this->getBasicToken();

        if (!$token) {
            throw new UnauthorizedException();
        }

        // Refactor!
        list($username, $password) = explode(":", base64_decode($token));
        if ($username == "admin" && $password == "admin") {
            // Make a token 
            $jwt = JWTFacade::encode(array(
                "aud" => "me",
                "uuid" => "64646464"
            ));

            $response = new HtmlResponse(json_encode(array('jwt' => $jwt)));
            return $response->withAddedHeader('content-type', 'application/json')->withStatus(200);
        }

        throw new UnauthorizedException();
    }
}
