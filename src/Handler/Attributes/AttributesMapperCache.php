<?php
declare(strict_types=1);

namespace OniBus\Handler\Attributes;

use OniBus\Handler\Attributes\AttributesMapperInterface;
use Psr\SimpleCache\CacheInterface;

/**
 * Decorator
 */
class AttributesMapperCache implements AttributesMapperInterface
{
    /**
     * @var AttributesMapperInterface
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

    public function __construct(AttributesMapperInterface $mapper, CacheInterface $cache, string $cacheKey)
    {
        $this->mapper = $mapper;
        $this->cache = $cache;
        $this->cacheKey = $cacheKey;
    }

    /**
     * @inheritDoc
     */
    public function mapHandlers(): array
    {
        if ($this->cache->has($this->cacheKey)) {
            return $this->cache->get($this->cacheKey);
        }

        $mapped = $this->mapper->mapHandlers();
        $this->cache->set($this->cacheKey, $mapped);
        return $mapped;
    }
}
