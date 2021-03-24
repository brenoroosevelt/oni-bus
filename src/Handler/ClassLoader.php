<?php
declare(strict_types=1);

namespace XBus\Handler;

class ClassLoader
{
    protected $class;
    protected $method;

    public function __construct(string $class, $method = "__invoke")
    {
        $this->class = $class;
        $this->method = $method;
    }
}
