<?php
declare(strict_types=1);

namespace OniBus\Event;

interface ProvidesEvent
{
    /**
     * @return Event[]
     */
    public function releaseEvents(): iterable;
}
