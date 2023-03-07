<?php

namespace redcathedral\phpMySQLAdminrest\Strategy;

use redcathedral\phpMySQLAdminrest\Implementations\HashSHA256;

/**
 * @brief AuthenticationStrategy
 * @description A authentication strategy, that allows for different strategies.
  */
abstract class AuthenticationStrategy {

    public function boot() { }
    public abstract function verify(String $username, HashSHA256 $hash) : ?bool;

}

