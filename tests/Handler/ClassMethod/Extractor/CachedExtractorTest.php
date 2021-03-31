<?php
declare(strict_types=1);

namespace OniBus\Test\Handler\ClassMethod\Extractor;

use OniBus\Handler\ClassMethod\ClassMethod;
use OniBus\Handler\ClassMethod\ClassMethodExtractor;
use OniBus\Handler\ClassMethod\Extractor\CachedExtractor;
use OniBus\Test\TestCase;
use Symfony\Component\Cache\Adapter\ArrayAdapter;
use Symfony\Component\Cache\Psr16Cache;

class CachedExtractorTest extends TestCase
{
    public function testShouldCacheExtractedClassMethods()
    {
        $dummyExtractor = new class implements ClassMethodExtractor {

            protected $count = 0;

            public function extractClassMethods(): array
            {
                $this->count++;
                return [new ClassMethod('msg', 'class', 'method')];
            }

            public function count(): int
            {
                return $this->count;
            }
        };

        $cache = new Psr16Cache(new ArrayAdapter());
        $cacheDecorator = new CachedExtractor($dummyExtractor, $cache, 'cacheKey');
        $classMethods1 = $cacheDecorator->extractClassMethods();
        $classMethods2 = $cacheDecorator->extractClassMethods();

        $this->assertEquals(1, $dummyExtractor->count());
        $this->assertTrue($cache->has('cacheKey'));
        $this->assertEquals($classMethods1, $classMethods2);
        $this->assertEquals($classMethods1, $dummyExtractor->extractClassMethods());
    }
}
