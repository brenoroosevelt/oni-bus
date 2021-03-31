<?php
declare(strict_types=1);

namespace OniBus\Handler\Builder;

use OniBus\Attributes\Handler;
use OniBus\Handler\ClassMethod\ClassMethodExtractor;
use OniBus\Handler\ClassMethod\Extractor\CachedExtractor;
use OniBus\Handler\ClassMethod\Extractor\ExtractorUsingAttribute;
use OniBus\Handler\ClassMethod\Extractor\MethodFirstParameterExtractor;
use OniBus\Handler\ClassMethod\Mapper\DirectMapper;
use OniBus\Handler\ClassMethod\Mapper\ThrowingExceptionMapper;
use OniBus\Handler\ClassMethodResolver;
use OniBus\Handler\HandlerResolver;
use Psr\Container\ContainerInterface;
use Psr\SimpleCache\CacheInterface;

final class Resolver
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var CacheInterface
     */
    private $cache;

    /**
     * @var string
     */
    private $cacheKey;

    /**
     * @var bool
     */
    private $throwingException;

    /**
     * @var array
     */
    private $handlers;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->throwingException = false;
        $this->handlers = [];
    }

    public static function new(ContainerInterface $container): self
    {
        return new self($container);
    }

    public function withCache(CacheInterface $cache, string $cacheKey): self
    {
        $this->cache = $cache;
        $this->cacheKey = $cacheKey;
        return $this;
    }

    public function withHandlers(array $handlersFQCN): self
    {
        $this->handlers = $handlersFQCN;
        return $this;
    }

    public function throwingExceptions(bool $throw = true): self
    {
        $this->throwingException = $throw;
        return $this;
    }

    public function mapByAttributes(string $attribute = Handler::class): HandlerResolver
    {
        return $this->build(new ExtractorUsingAttribute($attribute, $this->handlers));
    }

    public function mapByMethod(string $method = "__invoke"): HandlerResolver
    {
        return $this->build(new MethodFirstParameterExtractor($method, $this->handlers));
    }

    private function build(ClassMethodExtractor $extractor): HandlerResolver
    {
        if ($this->cache instanceof CacheInterface && !empty($this->cacheKey)) {
            $extractor = new CachedExtractor($extractor, $this->cache, $this->cacheKey);
        }

        $mapper = new DirectMapper(...$extractor->extractClassMethods());
        if ($this->throwingException) {
            $mapper = new ThrowingExceptionMapper($mapper);
        }

        return new ClassMethodResolver($this->container, $mapper);
    }
}
