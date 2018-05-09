<?php

namespace App\Exceptions;


class EtheriumException extends \Exception
{

    public function __construct($message)
    {
        parent::__construct($message);
    }

}
