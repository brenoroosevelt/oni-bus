<?php
declare(strict_types=1);

namespace OniBus\Test\Handler;

use Habemus\Container;
use OniBus\Handler\ClassMethod\ClassMethod;
use OniBus\Handler\ClassMethod\ClassMethodMapper;
use OniBus\Handler\ClassMethodResolver;
use OniBus\Message;
use OniBus\NamedMessage;
use OniBus\Test\Fixture\ClassHandler;
use OniBus\Test\TestCase;

class ClassMethodResolverTest extends TestCase
{
    public function testShouldResolveClassMethodWithContainer()
    {
        $message1 = new class implements NamedMessage {
            public function getMessageName(): string
            {
                return 'message1';
            }
        };

        $dummyMapper = new class implements ClassMethodMapper {
            public function map(Message $message): array
            {
                return [new ClassMethod('message1', ClassHandler::class, 'handle')];
            }
        };

        $resolver = new ClassMethodResolver(new Container(), $dummyMapper);
        $handler = $resolver->resolve($message1);
        $result = $handler($message1);
        $this->assertTrue($resolver->canResolve($message1));
        $this->assertEquals(500, $result);
    }
}
