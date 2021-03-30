<?php
declare(strict_types=1);

namespace OniBus\Event;

use Generator;

interface ProvidesEvent
{
    /**
     * @return Generator<Event>
     */
    public function releaseEvents(): Generator;
}
