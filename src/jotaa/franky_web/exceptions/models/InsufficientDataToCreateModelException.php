<?php declare(strict_types = 1);

namespace jotaa\franky_web\exceptions\models;

use DomainException;

class InsufficientDataToCreateModelException extends DomainException
{
    public function __construct($message, $code = 0, \Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    public function __toString()
    {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }
}
