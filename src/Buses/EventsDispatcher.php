<?php
declare(strict_types=1);

namespace XBus\Buses;

use XBus\Bus;
use XBus\Chain;
use XBus\ChainTrait;
use XBus\Event\ProvidesEvent;
use XBus\Message;

class EventsDispatcher implements Bus, Chain
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
