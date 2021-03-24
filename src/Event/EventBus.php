<?php
declare(strict_types=1);

namespace XBus\Event;

use XBus\Assertion;
use XBus\BusChain;
use XBus\Message;

class EventBus extends BusChain
{

    /**
     * @param  Message|Event $message
     * @return mixed
     */
    public function dispatch(Message $message)
    {
        Assertion::assertInstanceOf(Event::class, $message);
        return parent::dispatch($message);
    }
}