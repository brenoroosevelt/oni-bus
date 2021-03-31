<?php
declare(strict_types=1);

namespace OniBus\Test\Handler\Builder;

use Habemus\Container;
use OniBus\Attributes\CommandHandler;
use OniBus\Exception\UnresolvableMessageException;
use OniBus\Handler\Builder\Resolver;
use OniBus\NamedMessage;
use OniBus\Test\Fixture\GenericMessage;
use OniBus\Test\Fixture\HandlerUsingAttributes;
use OniBus\Test\TestCase;
use Symfony\Component\Cache\Adapter\ArrayAdapter;
use Symfony\Component\Cache\Psr16Cache;

class ResolverTest extends TestCase
{
    public function testShouldCreateResolverByMethodWithCache()
    {
        $handlers = [
            HandlerUsingAttributes::class
        ];

        $cache = new Psr16Cache(new ArrayAdapter());
        $resolver = Resolver::new(new Container())
            ->withHandlers($handlers)
            ->withCache($cache, 'handlers')
            ->mapByMethod();

        $handler = $resolver->resolve(new GenericMessage());
        $this->assertEquals(200, $handler(new GenericMessage()));
        $this->assertTrue($cache->has('handlers'));
    }

    public function testShouldCreateResolverByMethodThrowingExceptions()
    {
        $handlers = [
            HandlerUsingAttributes::class
        ];

        $resolver = Resolver::new(new Container())
            ->withHandlers($handlers)
            ->throwingExceptions()
            ->mapByMethod();

        $msg = new class implements NamedMessage {
            public function getMessageName(): string
            {
                return 'msg1';
            }
        };

        $this->expectException(UnresolvableMessageException::class);
        $resolver->resolve($msg);
    }

    public function testShouldCreateResolverByAttributesWithCache()
    {
        if (PHP_VERSION_ID < 80000) {
            $this->markTestSkipped();
            return;
        }
        
        $handlers = [
            HandlerUsingAttributes::class
        ];

        $cache = new Psr16Cache(new ArrayAdapter());
        $resolver = Resolver::new(new Container())
            ->withHandlers($handlers)
            ->withCache($cache, 'handlers')
            ->mapByAttributes(CommandHandler::class);

        $handler = $resolver->resolve(new GenericMessage());
        $this->assertEquals(100, $handler(new GenericMessage()));
        $this->assertTrue($cache->has('handlers'));
    }

    public function testShouldCreateResolverByAttributesThrowingExceptions()
    {
        if (PHP_VERSION_ID < 80000) {
            $this->markTestSkipped();
            return;
        }

        $handlers = [
            HandlerUsingAttributes::class
        ];

        $resolver = Resolver::new(new Container())
            ->withHandlers($handlers)
            ->throwingExceptions()
            ->mapByMethod();

        $msg = new class implements NamedMessage {
            public function getMessageName(): string
            {
                return 'msg1';
            }
        };

        $this->expectException(UnresolvableMessageException::class);
        $resolver->resolve($msg);
    }
}
