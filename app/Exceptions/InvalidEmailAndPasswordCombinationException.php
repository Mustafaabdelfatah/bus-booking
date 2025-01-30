<?php

namespace App\Exceptions;

use Exception;
use Symfony\Component\HttpFoundation\Response;

class InvalidEmailAndPasswordCombinationException extends Exception
{
    public function __construct(string $message = 'Invalid Credentials', int $code = Response::HTTP_UNAUTHORIZED)
    {
        parent::__construct($message, $code);
    }
    public function render($request)
    {
        return response()->json([
            'message' => $this->getMessage(),
        ], $this->getCode());
    }
}
