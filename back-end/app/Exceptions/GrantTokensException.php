<?php

namespace App\Exceptions;


class GrantTokensException extends \Exception
{

    public function __construct($message)
    {
        parent::__construct($message);
    }

}
