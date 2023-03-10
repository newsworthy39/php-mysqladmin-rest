<?php

namespace redcathedral\phpMySQLAdminrest\Strategy;

use redcathedral\phpMySQLAdminrest\Strategy\AuthenticationStrategy;
use redcathedral\phpMySQLAdminrest\Implementations\HashSHA256;

class InMemoryAuthenticationStrategy extends AuthenticationStrategy
{
    private $users ;

    public function addUser(string $username, HashSHA256 $hash)
    {
        $this->users [$username] = $hash;
    }

    public function verify(string $username, HashSHA256 $hash): ?bool
    {
        if (array_key_exists($username, $this->users)) {
            return  $hash->compare($this->users[$username]);
        }

        return false;
    }
}
