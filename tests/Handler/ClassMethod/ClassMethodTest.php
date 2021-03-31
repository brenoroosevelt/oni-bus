<?php
declare(strict_types=1);

namespace OniBus\Test\Handler\ClassMethod;

use OniBus\Handler\ClassMethod\ClassMethod;
use OniBus\Test\TestCase;

class ClassMethodTest extends TestCase
{
    public function testClassMethodConstructor()
    {
        $classMethod = new ClassMethod('message1', 'Class1', 'methodA');
        $this->assertEquals('message1', $classMethod->message());
        $this->assertEquals('Class1', $classMethod->class());
        $this->assertEquals('methodA', $classMethod->method());
    }
}
