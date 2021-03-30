<?php
declare(strict_types=1);

namespace OniBus\Test\Query;

use OniBus\Query\Filter;
use OniBus\Query\FilterTrait;
use OniBus\Test\TestCase;

class FilterTraitTest extends TestCase
{
    protected function newFilterTrait()
    {
        return new class {
            use FilterTrait;
        };
    }

    public function testShouldFilterTraitInitializeWithEmptyFilters()
    {
        $trait = $this->newFilterTrait();
        $this->assertFalse($trait->hasFilters());
    }

    public function testShouldFilterTraitAlwaysReturnInstanceOfFilter()
    {
        $trait = $this->newFilterTrait();
        $this->assertInstanceOf(Filter::class, $trait->filters());
    }

    public function testShouldFilterTraitEmptyWhenFiltersAreEmpty()
    {
        $trait = $this->newFilterTrait();
        $this->invokeMethod($trait, 'setFilters', [new Filter()]);
        $this->assertFalse($trait->hasFilters());
    }

    public function testShouldFilterTraitSetFilter()
    {
        $trait = $this->newFilterTrait();
        $this->invokeMethod($trait, 'setFilters', [new Filter(['field' => 'value'])]);
        $this->assertEquals('value', $trait->filters()->get('field'));
    }
}
