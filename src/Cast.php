<?php

namespace Siestacat\PhpArrayCast;

use Siestacat\PhpArrayCast\Exception\CastException;

/**
 * @package Siestacat\PhpArrayCast
 */
class Cast
{
    public static function class(array $array, string $class_name):void
    {
        array_map
        (
            function(mixed $value) use ($class_name):void
            {
                self::check_class($value, $class_name);
            },
            $array
        );
    }

    public static function type(array $array, string $type):void
    {
        array_map
        (
            function(mixed $value) use ($type):void
            {
                self::check_type($value, $type);
            },
            $array
        );
    }

    private static function check_class(mixed $value, string $class_name):void
    {
        if(!is_object($value) || (is_object($value) && get_class($value) !== $class_name)) throw new CastException($class_name, $value);
    }

    private static function check_type(mixed $value, string $type):void
    {
        if(gettype($value) != $type) throw new CastException($type, $value);
    }
    
}