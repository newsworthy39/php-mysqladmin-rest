<?php

namespace redcathedral\phpMySQLAdminrest\Strategy;

use redcathedral\phpMySQLAdminrest\Implementations\HashSHA256;

/**
 * @brief       AuthenticationStrategy
 * @description A authentication strategy, that allows for different strategies.
 */
abstract class AuthenticationStrategy
{
    public function boot()
    {
    }
    abstract public function verify(string $username, HashSHA256 $hash): ?bool;
}
