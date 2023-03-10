<?php

namespace redcathedral\phpMySQLAdminrest\Facades;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

use function redcathedral\phpMySQLAdminrest\App;

/**
 * @description JWTFacade is a container-managed front to issue JWTs.
 * @author Newsworthy39 <newsworthy39@github.com
 * @license BSD-3
 */
class JWTFacade
{
    // Private member variables
    private $privkey;
    private $pubkey;
    private $issuerdomain;

    /**
     * Constructor
     * @param privkey a PEM-encoded private-key
     * @param pubkey  a PEM-encoded public-key
     * @param issuerdomain the domain that JWT should use as a signee.
     */
    public function __construct($privkey, $pubkey, $issuerdomain)
    {
        $this->privkey = $privkey;
        $this->pubkey = $pubkey;
        $this->issuerdomain = $issuerdomain;
    }

    private function __encode($token)
    {
        $jwt = $token;
        $jwt["iss"]  = $this->issuerdomain;
        $jwt["iat"] = time();
        $jwt["nbf"] = $jwt["iat"];
        $jwt["exp"] = $jwt["iat"] + 86400;

        return JWT::encode($jwt, $this->privkey, 'RS256');
    }

    /**
     *
     */
    private function __decode($token)
    {
        list($header, $payload, $signature) = explode(".", $token);
        $plainHeader = base64_decode($header);
        $plainPayload = json_decode(base64_decode($payload));
        return array($plainHeader, $plainPayload);
    }

    private function __verify($token)
    {
        return JWT::decode($token, new Key($this->pubkey, 'RS256'));
    }

    private function __getIssuer()
    {
        return $this->issuerdomain;
    }

    public static function encode($token)
    {
        return (App()->get(\redcathedral\phpMySQLAdminrest\Facades\JWTFacade::class))->__encode($token);
    }

    public static function decode($token)
    {
        return (App()->get(\redcathedral\phpMySQLAdminrest\Facades\JWTFacade::class))->__decode($token);
    }

    public static function verify($token)
    {
        return (App()->get(\redcathedral\phpMySQLAdminrest\Facades\JWTFacade::class))->__verify($token);
    }

    public static function getIssuer()
    {
        return (App()->get(\redcathedral\phpMySQLAdminrest\Facades\JWTFacade::class))->__getIssuer();
    }
}
