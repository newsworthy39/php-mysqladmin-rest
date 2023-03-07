<?php

namespace redcathedral\phpMySQLAdminrest\Implementations;

use redcathedral\phpMySQLAdminrest\Interfaces\AuthenticationProxyInterface;

class FileAuthenticationImpl extends AuthenticationProxyInterface
{
    private $users ;

    public function addUser(String $username, HashSHA256 $hash) {
        $this->users [$username] = $hash;
    }

    public function verify(String $username, HashSHA256 $hash): ?bool
    {
        if(array_key_exists($username, $this->users)) {
            return  $hash->compare($this->users[$username]);
        }

        return false;
    }
}

