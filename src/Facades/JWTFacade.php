<?php

namespace redcathedral\phpMySQLAdminrest\Facades;

use \Firebase\JWT\JWT;
use \Firebase\JWT\Key;
use function \redcathedral\phpMySQLAdminrest\App;

/**
 * @brief JWTFacade is a container-managed front to issue JWTs.
 */
class JWTFacade
{

    private $privkey;
    private $pubkey;
    private $issuerdomain;

    public function __construct($privkey, $pubkey, $issuerdomain)
    {
        $this->privkey = $privkey;
        $this->pubkey = $pubkey;
        $this->issuerdomain = $issuerdomain;
    }

    private function _encode($token)
    {
        $jwt = $token;
        $jwt["iss"]  = $this->issuerdomain;
        $jwt["iat"] = time();
        $jwt["nbf"] = $jwt["iat"];
        $jwt["exp"] = $jwt["iat"] + 86400;

        return JWT::encode($jwt, $this->privkey, 'RS256');
    }

    private function _decode($token)
    {
        list($header, $payload, $signature) = explode(".", $token);
        $plainHeader = base64_decode($header);
        $plainPayload = json_decode(base64_decode($payload));
        return array($plainHeader, $plainPayload);
    }

    private function _verify($token)
    {
        return JWT::decode($token, new Key($this->pubkey, 'RS256'));
    }

    private function _getIssuer()
    {
        return $this->issuerdomain;
    }

    public static function encode($token)
    {
        return (App()->get(\redcathedral\phpMySQLAdminrest\Facades\JWTFacade::class))->_encode($token);
    }

    public static function decode($token)
    {
        return (App()->get(\redcathedral\phpMySQLAdminrest\Facades\JWTFacade::class))->_decode($token);
    }

    public static function verify($token)
    {
        return (App()->get(\redcathedral\phpMySQLAdminrest\Facades\JWTFacade::class))->_verify($token);
    }

    public static function getIssuer()
    {
        return (App()->get(\redcathedral\phpMySQLAdminrest\Facades\JWTFacade::class))->_getIssuer();
    }
}
