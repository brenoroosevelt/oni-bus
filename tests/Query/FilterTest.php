<?php
declare(strict_types=1);

namespace OniBus\Test\Query;

use OniBus\Query\Filter;
use OniBus\Test\TestCase;

class FilterTest extends TestCase
{
    public function testShouldAddFilter()
    {
        $filter = new Filter(['field1' => 'value1']);
        $this->assertFalse($filter->has('field2'));
        $filter->add('field2', 'value2');
        $this->assertTrue($filter->has('field2'));
        $this->assertEquals('value2', $filter->field2);
        $this->assertEquals('value2', $filter->field2());
        $this->assertEquals('value2', $filter->get('field2'));
    }
}
