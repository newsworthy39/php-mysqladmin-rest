<?php

namespace redcathedral\phpMySQLAdminrest\Implementations;

final class HashSHA256
{
    private $hash;

    private function __construct()
    {
    }

    public static function fromString(string $password): HashSHA256
    {
        $obj = new HashSHA256();
        $obj->hash = hash('sha256', $password);
        return $obj;
    }

    public static function fromHash(string $hash): HashSHA256
    {
        $obj = new HashSHA256();
        $obj->hash = $hash;
        return $obj;
    }

    public function compare(HashSHA256 $other)
    {
        return $this->hash == $other->hash;
    }
}
