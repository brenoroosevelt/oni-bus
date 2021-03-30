<?php
declare(strict_types=1);

namespace OniBus\Test\Utility;

use OniBus\Test\Fixture\SingletonClass;
use OniBus\Test\TestCase;
use RuntimeException;
use Throwable;

class SingletonTest extends TestCase
{
    public function testShouldNotCreateInstancesOfSingleton()
    {
        $this->expectException(Throwable::class);
        new SingletonClass();
    }

    public function testShouldSingletonGetSameInstance()
    {
        $instance1 = SingletonClass::instance();
        $instance2 = SingletonClass::instance();
        $this->assertSame($instance1, $instance2);
    }

    public function testShouldSingletonGetAlwaysSameInstance()
    {
        $instance1 = SingletonClass::instance();
        $instance2 = SingletonClass::instance();
        $this->assertSame($instance1, $instance2);
    }

    public function testShouldSingletonCallStatic()
    {
        $this->assertEquals(100, SingletonClass::foo());
    }

    public function testShouldSingletonThrowsErrorWhenMethodDoesNotExists()
    {
        $this->expectException(RuntimeException::class);
        SingletonClass::bar();
    }
}
