<?php
declare(strict_types=1);

namespace OniBus\Event;

use OniBus\Utility\Singleton;

/**
 * @method static EventProvider instance()
 */
final class EventManager
{
    use Singleton;

    protected static function singleInstance(): EventProvider
    {
        return new EventProvider();
    }

    public static function recordEvent(Event ...$events): void
    {
        self::instance()->recordEvent(...$events);
    }

    /**
     * @return Event[]
     */
    public static function releaseEvents(): iterable
    {
        return self::instance()->releaseEvents();
    }
}
