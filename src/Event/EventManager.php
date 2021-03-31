<?php
declare(strict_types=1);

namespace OniBus\Event;

use Generator;

final class EventManager
{
    private static $eventProvider = null;

    private function __construct()
    {
    }

    public static function eventProvider(): EventProvider
    {
        if (is_null(self::$eventProvider)) {
            self::$eventProvider = new EventProvider();
        }

        return self::$eventProvider;
    }

    public static function recordEvent(Event ...$events): void
    {
        self::eventProvider()->recordEvent(...$events);
    }

    public static function releaseEvents(): Generator
    {
        return self::eventProvider()->releaseEvents();
    }
}
