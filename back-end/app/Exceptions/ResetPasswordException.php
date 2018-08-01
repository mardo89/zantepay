<?php

namespace App\Exceptions;


class ResetPasswordException extends \Exception
{

    public function __construct()
    {
        $message = 'Error while resetting password';

        parent::__construct($message);
    }

}
