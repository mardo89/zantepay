<?php

namespace App\Exceptions;


class AuthException extends \Exception
{

    public function __construct()
    {
        $message = 'Authentication failed';

        parent::__construct($message);
    }

}
