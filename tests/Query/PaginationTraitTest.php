<?php
declare(strict_types=1);

namespace OniBus\Test\Query;

use OniBus\Query\Pagination;
use OniBus\Query\PaginationTrait;
use OniBus\Test\TestCase;

class PaginationTraitTest extends TestCase
{
    protected function newPaginationTrait()
    {
        return new class {
            use PaginationTrait;
        };
    }

    public function testShouldPaginationTraitInitializeWithEmptyPagination()
    {
        $trait = $this->newPaginationTrait();
        $this->assertFalse($trait->hasPagination());
    }

    public function testShouldPaginationTraitReturnNull()
    {
        $trait = $this->newPaginationTrait();
        $this->assertNull($trait->pagination());
    }

    public function testShouldPaginationTraitSetPagination()
    {
        $trait = $this->newPaginationTrait();
        $this->invokeMethod($trait, 'setPagination', [new Pagination(1, 20)]);
        $this->assertEquals(1, $trait->pagination()->page());
        $this->assertEquals(20, $trait->pagination()->limit());
    }
}
