<?php

namespace redcathedral\phpMySQLAdminrest\Interfaces;

use redcathedral\phpMySQLAdminrest\Implementations\HashSHA256;

/**
 * @brief AuthenticationProxyInterface
 * @description 
 * 
 */
abstract class AuthenticationProxyInterface {

    public function boot() { }
    public abstract function verify(String $username, HashSHA256 $hash) : ?bool;

}

