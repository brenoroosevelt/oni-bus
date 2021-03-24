<?php
declare(strict_types=1);

namespace XBus\Event;

interface ProvidesEvent
{
    /**
     * @return Event[]
     */
    public function releaseEvents(): iterable;
}
