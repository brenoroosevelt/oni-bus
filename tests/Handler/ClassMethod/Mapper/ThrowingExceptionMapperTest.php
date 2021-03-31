<?php
declare(strict_types=1);

namespace OniBus\Test\Handler\ClassMethod\Mapper;

use OniBus\Exception\UnresolvableMessageException;
use OniBus\Handler\ClassMethod\ClassMethod;
use OniBus\Handler\ClassMethod\Mapper\DirectMapper;
use OniBus\Handler\ClassMethod\Mapper\ThrowingExceptionMapper;
use OniBus\NamedMessage;
use OniBus\Test\TestCase;

class ThrowingExceptionMapperTest extends TestCase
{
    public function testThrowingExceptionMapperNotThrowsException()
    {
        $msg2 = new class implements NamedMessage {
            public function getMessageName(): string
            {
                return 'msg2';
            }
        };

        $mapper = new ThrowingExceptionMapper(
            new DirectMapper(new ClassMethod('msg2', 'class1', 'method'))
        );

        $result = $mapper->map($msg2);
        $this->assertEquals([new ClassMethod('msg2', 'class1', 'method')], $result);
    }

    public function testThrowingExceptionMapper()
    {
        $msg1 = new class implements NamedMessage {
            public function getMessageName(): string
            {
                return 'msg1';
            }
        };

        $mapper = new ThrowingExceptionMapper(
            new DirectMapper(new ClassMethod('msg2', 'class1', 'method'))
        );

        $this->expectException(UnresolvableMessageException::class);
        $mapper->map($msg1);
    }
}
