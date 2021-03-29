<?php
declare(strict_types=1);

namespace OniBus\Event;

use OniBus\BusChain;
use OniBus\Message;
use OniBus\Utility\Assert;

class EventBus extends BusChain
{
    /**
     * @param  Message|Event $event
     * @return mixed
     */
    public function dispatch(Message $event)
    {
        Assert::instanceOf(Event::class, $event, $this);
        return parent::dispatch($event);
    }
}
