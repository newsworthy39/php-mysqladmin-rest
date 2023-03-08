<?php

declare(strict_types=1);

namespace redcathedral\phpMySQLAdminrest\Exception;

use Exception;
use League\Route\Http;

class ServerErrorException extends Http\Exception
{
    public function __construct(string $message = 'Server Error', ?Exception $previous = null, int $code = 0)
    {
        parent::__construct(500, $message, $previous, [], $code);
    }
}
