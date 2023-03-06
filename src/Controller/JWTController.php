<?php

namespace redcathedral\phpMySQLAdminrest\Controller;

use Psr\Http\Message\ServerRequestInterface;
use redcathedral\phpMySQLAdminrest\Facades\JWTFacade;

class JWTController
{
    public function getJWTToken(ServerRequestInterface $request, array $args): array
    {
        // Make a token 
        $jwt = JWTFacade::encode(array(
            "aud" => "me",
            "uuid" => "64646464"
        ));

        return array('jwt' => $jwt);
    }
}
