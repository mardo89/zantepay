<?php

namespace App\Exceptions;


class UserNotFoundException extends \Exception
{

    public function __construct()
    {
        $message = 'User does not exist';

        parent::__construct($message);
    }

}
