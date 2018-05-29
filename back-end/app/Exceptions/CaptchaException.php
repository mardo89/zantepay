<?php

namespace App\Exceptions;


class CaptchaException extends \Exception
{

    public function __construct()
    {
        $message = 'Invalid captcha. Please try again.';

        parent::__construct($message);
    }

}
