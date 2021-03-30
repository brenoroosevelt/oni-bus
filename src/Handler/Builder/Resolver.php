<?php
declare(strict_types=1);

namespace OniBus\Handler\Builder;

use OniBus\Attributes\Handler;
use OniBus\Handler\ClassMethod\ClassMethodExtractor;
use OniBus\Handler\ClassMethod\Extractor\CachedExtractor;
use OniBus\Handler\ClassMethod\Extractor\ExtractorUsingAttribute;
use OniBus\Handler\ClassMethod\Extractor\MethodFirstParameterExtractor;
use OniBus\Handler\ClassMethod\Mapper\DirectMapper;
use OniBus\Handler\ClassMethodResolver;
use OniBus\Handler\HandlerResolver;
use Psr\Container\ContainerInterface;
use Psr\SimpleCache\CacheInterface;

final class Resolver
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @var CacheInterface
     */
    private $cache;

    /**
     * @var string
     */
    private $cacheKey;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public static function new(ContainerInterface $container): self
    {
        return new self($container);
    }

    public function useCache(CacheInterface $cache, string $cacheKey): self
    {
        $this->cache = $cache;
        $this->cacheKey = $cacheKey;
        return $this;
    }

    public function inflectByAttribute(array $handlersFQCN, string $attribute = Handler::class): HandlerResolver
    {
        return $this->build(new ExtractorUsingAttribute($attribute, $handlersFQCN));
    }

    public function inflectByMethod(array $handlersFQCN, string $method = "__invoke"): HandlerResolver
    {
        return $this->build(new MethodFirstParameterExtractor($method, $handlersFQCN));
    }

    private function build(ClassMethodExtractor $extractor): HandlerResolver
    {
        if ($this->cache instanceof CacheInterface && !empty($this->cacheKey)) {
            $extractor = new CachedExtractor($extractor, $this->cache, $this->cacheKey);
        }

        return new ClassMethodResolver(
            $this->container,
            new DirectMapper(...$extractor->extractClassMethods())
        );
    }
}
