<?php
declare(strict_types=1);

namespace OniBus\Event;

use Generator;

trait EventProviderTrait
{
    /**
     * @var array
     */
    protected $events = [];

    /**
     * @var array
     */
    protected $aggregateEvents = [];

    public function recordEvent(Event ...$events)
    {
        foreach ($events as $event) {
            $this->events[] = $event;
        }
    }

    public function aggregateEventsFrom(ProvidesEvent ...$eventRecorders)
    {
        foreach ($eventRecorders as $eventRecorder) {
            $this->aggregateEvents[] = $eventRecorder;
        }
    }

    public function pullEventsFrom(ProvidesEvent ...$eventRecorders)
    {
        foreach ($eventRecorders as $eventRecorder) {
            $this->recordEvent(...$eventRecorder->releaseEvents());
        }
    }

    public function releaseEvents(): Generator
    {
        $this->pullEventsFrom(...$this->aggregateEvents);

        while ($event = array_shift($this->events)) {
            yield $event;
        }
    }
}
