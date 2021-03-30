<?php
declare(strict_types=1);

namespace OniBus\Handler\ClassMethod\Extractor;

use OniBus\Handler\ClassMethod\ClassMethodExtractor;
use Psr\SimpleCache\CacheInterface;

/**
 * Decorator
 */
class CachedExtractor implements ClassMethodExtractor
{
    /**
     * @var ClassMethodExtractor
     */
    protected $mapper;

    /**
     * @var string
     */
    private $cacheKey;

    /**
     * @var CacheInterface
     */
    private $cache;

    public function __construct(ClassMethodExtractor $mapper, CacheInterface $cache, string $cacheKey)
    {
        $this->mapper = $mapper;
        $this->cache = $cache;
        $this->cacheKey = $cacheKey;
    }

    /**
     * @inheritDoc
     */
    public function extractClassMethods(): array
    {
        if ($this->cache->has($this->cacheKey)) {
            return $this->cache->get($this->cacheKey);
        }

        $mapped = $this->mapper->extractClassMethods();
        $this->cache->set($this->cacheKey, $mapped);
        return $mapped;
    }
}
