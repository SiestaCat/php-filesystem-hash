<?php

namespace Siestacat\Phpfilemanager\Tests\File;
use PHPUnit\Framework\TestCase;
use Siestacat\PhpArrayCast\Cast;
use Siestacat\PhpArrayCast\Exception\CastException;

class CastTest extends TestCase
{
    public function test_cast_class_failed():void
    {
        $this->expectException(CastException::class);

        Cast::class(range(0,9), \stdClass::class);
    }

    public function test_cast_class():void
    {
        $this->expectException(CastException::class);

        Cast::class(array_fill(0, 9, new \stdClass), \stdClass::class);
    }

    public function test_cast_type_failed():void
    {
        $this->expectException(CastException::class);

        Cast::type(range(0,9), 'string');
    }

    public function test_cast_type():void
    {
        $this->expectException(CastException::class);

        Cast::class(array_fill(0, 9, 'banana'), 'string');
    }
}