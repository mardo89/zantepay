<?php

namespace App\Exceptions;


class VerificationException extends \Exception
{

    public function __construct($message)
    {
        parent::__construct($message);
    }

}
