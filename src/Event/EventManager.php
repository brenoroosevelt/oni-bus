<?php
declare(strict_types=1);

namespace OniBus\Event;

use OniBus\Utility\Singleton;

/**
 * @method static void recordEvent(Event ...$events)
 * @method static void aggregateEventsFrom(ProvidesEvent ...$eventRecorders)
 * @method static void pullEventsFrom(ProvidesEvent ...$eventRecorders)
 */
class EventManager implements ProvidesEvent
{
    use EventProviderTrait;
    use Singleton;

    protected static function createInstance(): self
    {
        return new self();
    }
}
