<?php
declare(strict_types=1);

namespace OniBus\Event;

use OniBus\Utility\Singleton;

/**
 * @method static void recordEvent(Event ...$events)
 * @method static void aggregateEventsFrom(ProvidesEvent ...$eventRecorders)
 * @method static void pullEventsFrom(ProvidesEvent ...$eventRecorders)
 */
final class EventManager extends EventProvider
{
    use Singleton;

    protected static function singleInstance(): self
    {
        return new self();
    }
}
