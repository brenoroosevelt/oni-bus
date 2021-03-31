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
    protected $extractor;

    /**
     * @var string
     */
    private $cacheKey;

    /**
     * @var CacheInterface
     */
    private $cache;

    public function __construct(ClassMethodExtractor $extractor, CacheInterface $cache, string $cacheKey)
    {
        $this->extractor = $extractor;
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

        $classMethods = $this->extractor->extractClassMethods();
        $this->cache->set($this->cacheKey, $classMethods);
        return $classMethods;
    }
}
