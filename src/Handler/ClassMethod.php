<?php
declare(strict_types=1);

namespace XBus\Handler;

class ClassMethod
{
    /**
     * @var string
     */
    protected $class;

    /**
     * @var string
     */
    protected $method;

    public function __construct(string $class, string $method)
    {
        $this->class = $class;
        $this->method = $method;
    }

    public function class(): string
    {
        return $this->class;
    }

    public function method(): string
    {
        return $this->method;
    }
}
