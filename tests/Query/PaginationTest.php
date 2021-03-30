<?php
declare(strict_types=1);

namespace OniBus\Test\Query;

use InvalidArgumentException;
use OniBus\Query\Pagination;
use OniBus\Test\TestCase;

class PaginationTest extends TestCase
{
    public function testShouldCreatePagination()
    {
        $pagination = new Pagination(2, 10);
        $this->assertEquals(2, $pagination->page());
        $this->assertEquals(10, $pagination->limit());
    }

    public function testShouldPaginationThrowsExceptionWhenInvalidPage()
    {
        $this->expectException(InvalidArgumentException::class);
        new Pagination(Pagination::MIN_PAGE_NUMBER -1, 20);
    }

    public function testShouldPaginationThrowsExceptionWhenInvalidLimit()
    {
        $this->expectException(InvalidArgumentException::class);
        new Pagination(1, Pagination::MIN_PAGE_LIMIT -1);
    }
}
