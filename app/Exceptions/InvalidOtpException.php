<?php

namespace App\Exceptions;

use Exception;

class InvalidOtpException extends Exception
{
    public function __construct($message = "Invalid OTP")
    {
        parent::__construct($message);
    }
}
