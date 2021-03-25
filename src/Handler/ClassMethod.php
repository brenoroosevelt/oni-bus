<?php
declare(strict_types=1);

namespace XBus\Handler;

class ClassMethod
{
    /**
     * @var string
     */
    private $message;

    /**
     * @var string
     */
    protected $class;

    /**
     * @var string
     */
    protected $method;

    public function __construct(string $message, string $class, string $method)
    {
        $this->message = $message;
        $this->class = $class;
        $this->method = $method;
    }

    public function message(): string
    {
        return $this->message;
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
