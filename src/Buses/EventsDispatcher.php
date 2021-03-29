<?php
declare(strict_types=1);

namespace OniBus\Buses;

use OniBus\Bus;
use OniBus\Chain;
use OniBus\ChainTrait;
use OniBus\Event\ProvidesEvent;
use OniBus\Message;

class EventsDispatcher implements Chain
{
    use ChainTrait;

    /**
     * @var Bus
     */
    protected $eventBus;

    /**
     * @var ProvidesEvent
     */
    protected $providesEvent;

    public function __construct(ProvidesEvent $providesEvent, Bus $eventBus)
    {
        $this->providesEvent = $providesEvent;
        $this->eventBus = $eventBus;
    }

    public function dispatch(Message $message)
    {
        $result = $this->next($message);
        foreach ($this->providesEvent->releaseEvents() as $event) {
            $this->eventBus->dispatch($event);
        }

        return $result;
    }
}
