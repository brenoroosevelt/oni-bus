<?php
declare(strict_types=1);

namespace OniBus\Test\Utility;

use IteratorAggregate;
use LogicException;
use OniBus\Test\TestCase;
use OniBus\Utility\KeyValueList;

class KeyValueListTest extends TestCase
{
    public function newTraitInstance()
    {
        return new class {
            use KeyValueList;
        };
    }

    public function testShouldKeyValueListImplementsIteratorAggregate()
    {
        $list = new class implements IteratorAggregate {
            use KeyValueList;
        };
        $this->assertInstanceOf(IteratorAggregate::class, $list);
    }

    public function testShouldCreateEmptyKeyValueList()
    {
        $list = $this->newTraitInstance();
        $this->assertEquals(0, $list->count());
        $this->assertTrue($list->isEmpty());
    }

    public function testShouldAddKeyValueList()
    {
        $list = $this->newTraitInstance();
        $list->set(1, "a");
        $this->assertTrue($list->has(1));
        $this->assertEquals("a", $list->get(1));
    }

    public function testShouldKeyValueListNotEmptyAfterInsert()
    {
        $list = $this->newTraitInstance();
        $list->set(1, "a");
        $this->assertFalse($list->isEmpty());
    }

    public function testShouldKeyValueListCount()
    {
        $list = $this->newTraitInstance();
        $list->set(1, "a");
        $list->set(2, "b");
        $list->set(3, "c");
        $this->assertEquals(3, $list->count());
    }

    public function testShouldKeyValueListDeleteItem()
    {
        $list = $this->newTraitInstance();
        $list->set(1, "a");
        $list->delete(1);
        $this->assertFalse($list->has(1));
    }

    public function testShouldKeyValueListDeleteItemEvenRepeated()
    {
        $list = $this->newTraitInstance();
        $list->set(1, "a");
        $list->set(1, "b");
        $list->set(1, "c");
        $list->delete(1);
        $this->assertFalse($list->has(1));
    }

    public function testShouldKeyValueListCountAfterDelete()
    {
        $list = $this->newTraitInstance();
        $list->set(1, "a");
        $list->set(2, "b");
        $list->set(2, "c");
        $list->set(3, "d");
        $list->delete(2);
        $this->assertEquals(2, $list->count());
    }

    public function testShouldNotKeyValueListCountRepeated()
    {
        $list = $this->newTraitInstance();
        $list->set(1, "a");
        $list->set(2, "b");
        $list->set(3, "c");
        $list->set(1, "d");
        $list->set(2, "e");
        $this->assertEquals(3, $list->count());
    }

    public function testShouldKeyValueListThrowsErrorIfItemNotExists()
    {
        $list = $this->newTraitInstance();
        $this->expectException(LogicException::class);
        $list->get(1);
    }

    public function testShouldIterateKeyValueList()
    {
        $list = $list = new class implements IteratorAggregate {
            use KeyValueList;
        };
        $list->set(1, "a");
        $list->set(2, "b");
        $list->set(3, "c");
        foreach ($list as $id => $value) {
            $this->assertTrue(in_array($id, [1, 2, 3], true));
            $this->assertTrue(in_array($value, ['a', 'b','c'], true));
        }
    }

    public function testShouldGetIteratorKeyValueList()
    {
        $list = $this->newTraitInstance();
        $list->set(1, "a");
        $list->set(2, "b");
        $list->set(3, "c");
        foreach ($list->getIterator() as $id => $value) {
            $this->assertTrue(in_array($id, [1, 2, 3], true));
            $this->assertTrue(in_array($value, ['a', 'b','c'], true));
        }
    }
}
