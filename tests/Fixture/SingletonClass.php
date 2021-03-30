<?php
declare(strict_types=1);

namespace OniBus\Test\Fixture;

use OniBus\Utility\Singleton;

class SingletonClass
{
    use Singleton;

    protected static function singleInstance()
    {
        return new self();
    }

    public function foo(): int
    {
        return 100;
    }
}
