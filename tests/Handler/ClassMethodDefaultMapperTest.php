<?php
declare(strict_types=1);

namespace OniBus\Test\Handler;

use OniBus\Handler\ClassMethod;
use OniBus\Handler\ClassMethodDefaultMapper;
use OniBus\NamedMessage;
use OniBus\Test\Fixture\ClassHandler;
use OniBus\Test\TestCase;

class ClassMethodDefaultMapperTest extends TestCase
{
    public function testMapper()
    {
        $message1 = new class implements NamedMessage {
            public function getMessageName(): string
            {
                return 'message1';
            }
        };

        $message2 = new class implements NamedMessage {
            public function getMessageName(): string
            {
                return 'message2';
            }
        };

        $mapper = new ClassMethodDefaultMapper([
            'message1' => ClassHandler::class,
            'message2' => ClassHandler::class,
        ], 'handle');

        $result1 = $mapper->map($message1);
        $result2 = $mapper->map($message2);

        $this->assertEquals(new ClassMethod('message1', ClassHandler::class, 'handle'), $result1[0]);
        $this->assertEquals(new ClassMethod('message2', ClassHandler::class, 'handle'), $result2[0]);
    }

    public function testShouldClassMethodDefaultMapperSkipsClassMethodDoesNotExists()
    {
        $message1 = new class implements NamedMessage {
            public function getMessageName(): string
            {
                return 'message1';
            }
        };

        $mapper = new ClassMethodDefaultMapper([
            'message1' => 'ClassA',
        ], 'handle');

        $result1 = $mapper->map($message1);
        $this->assertEmpty($result1);
    }
}
