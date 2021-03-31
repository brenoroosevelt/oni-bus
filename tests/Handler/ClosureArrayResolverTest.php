<?php
declare(strict_types=1);

namespace OniBus\Test\Handler;

use OniBus\Exception\UnresolvableMessageException;
use OniBus\Handler\ClosureArrayResolver;
use OniBus\Message;
use OniBus\NamedMessage;
use OniBus\Test\TestCase;

class ClosureArrayResolverTest extends TestCase
{
    public function testShouldConstructWithArrayAndResolveMessage()
    {
        $message = new class implements NamedMessage {
            public function getMessageName(): string
            {
                return 'message1';
            }
        };

        $execution = [];
        $resolver = new ClosureArrayResolver([
            'message1' => function (Message $message) use (&$execution) {
                $execution[] = 1;
            }
        ]);

        $handler = $resolver->resolve($message);
        $handler($message);

        $this->assertTrue(in_array(1, $execution));
    }

    public function testShouldConstructSkipsInvalidClosure()
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

        $resolver = new ClosureArrayResolver([
            'message1' => function (Message $message) {
            },
            'message2' => 'invalidClosure'
        ]);

        $this->expectException(UnresolvableMessageException::class);
        $resolver->resolve($message2);
    }

    public function testShouldClosureArrayResolverThrowsException()
    {
        $message = new class implements NamedMessage {
            public function getMessageName(): string
            {
                return 'message1';
            }
        };

        $resolver = new ClosureArrayResolver([]);
        $this->expectException(UnresolvableMessageException::class);
        $resolver->resolve($message);
    }
}
