<?php
declare(strict_types=1);

namespace OniBus\Event;

use Generator;

final class EventManager extends EventProvider
{
    private static $eventProvider = null;

    private function __construct()
    {
    }

    public static function eventProvider(): EventProvider
    {
        if (is_null(self::$eventProvider)) {
            self::$eventProvider = new self();
        }

        return self::$eventProvider;
    }

    public static function record(Event ...$events): void
    {
        self::eventProvider()->recordEvent(...$events);
    }

    public static function release(): Generator
    {
        return self::eventProvider()->releaseEvents();
    }
}
