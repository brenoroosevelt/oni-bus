<?php
declare(strict_types=1);

namespace OniBus\Test\Fixture;

use OniBus\NamedMessage;

class DummyMessage implements NamedMessage
{
    protected $name;

    public function __construct($name)
    {
        $this->name = $name;
    }

    public function getMessageName(): string
    {
        return $this->name;
    }
}
