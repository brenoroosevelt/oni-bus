<?php
declare(strict_types=1);

namespace OniBus\Test\Handler\ClassMethod\Mapper;

use OniBus\Handler\ClassMethod\ClassMethod;
use OniBus\Handler\ClassMethod\Mapper\DirectMapper;
use OniBus\NamedMessage;
use OniBus\Test\TestCase;

class DirectMapperTest extends TestCase
{
    public function testDirectMapperBinding()
    {
        $msg1 = new class implements NamedMessage {
            public function getMessageName(): string
            {
                return 'msg1';
            }
        };

        $msg2 = new class implements NamedMessage {
            public function getMessageName(): string
            {
                return 'msg2';
            }
        };

        $msg3 = new class implements NamedMessage {
            public function getMessageName(): string
            {
                return 'msg3';
            }
        };

        $mapper =new DirectMapper(...[
            new ClassMethod('msg1', 'class1', 'method'),
            new ClassMethod('msg1', 'class2', 'method'),
            new ClassMethod('msg2', 'class1', 'method'),
        ]);

        $result1 = $mapper->map($msg1);
        $result2 = $mapper->map($msg2);
        $result3 = $mapper->map($msg3);

        $this->assertEquals([
            new ClassMethod('msg1', 'class1', 'method'),
            new ClassMethod('msg1', 'class2', 'method'),
        ], $result1);

        $this->assertEquals([
            new ClassMethod('msg2', 'class1', 'method'),
        ], $result2);

        $this->assertEmpty($result3);
    }
}
