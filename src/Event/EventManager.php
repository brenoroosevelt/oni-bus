<?php
declare(strict_types=1);

namespace OniBus\Event;

use OniBus\Utility\Singleton;

class EventManager implements ProvidesEvent
{
    use EventProviderTrait;
    use Singleton;

    protected static function createInstance(): self
    {
        return new self();
    }
}
