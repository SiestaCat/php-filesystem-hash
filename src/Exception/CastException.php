<?php

namespace Siestacat\PhpArrayCast\Exception;

/**
 * @package Siestacat\PhpArrayCast\Exception
 */
class CastException extends \Exception
{
    public function __construct(string $required_type, mixed $value)
    {
        parent::__construct(sprintf('Array elements must be of type %s, %s given', $required_type, gettype($value)));
    }
}