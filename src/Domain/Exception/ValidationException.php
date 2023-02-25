<?php

namespace App\Domain\Exception;

final class ValidationException extends \Exception
{
    public function __construct(string $message)
    {
        parent::__construct($message);
    }
}
