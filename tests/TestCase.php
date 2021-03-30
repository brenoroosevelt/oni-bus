<?php
declare(strict_types=1);

namespace OniBus\Test;

use ReflectionClass;

class TestCase extends \PHPUnit\Framework\TestCase
{
    protected static function invokeMethod($object, $methodName, array $parameters = [])
    {
        $reflection = new ReflectionClass(get_class($object));
        $method = $reflection->getMethod($methodName);
        $method->setAccessible(true);
        return $method->invokeArgs($object, $parameters);
    }
}
