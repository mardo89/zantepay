<?php

namespace App\Exceptions;


class TransactionException extends \Exception
{

    public function __construct($message)
    {
        parent::__construct($message);
    }

}
