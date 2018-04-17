<?php

namespace App\Exceptions;


class UserAccessException extends \Exception
{

    public function __construct()
    {
        $message = 'User has no access to himself';

        parent::__construct($message);
    }

}
