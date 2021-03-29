<?php
declare(strict_types=1);

namespace OniBus\Event;

use InvalidArgumentException;
use OniBus\BusChain;
use OniBus\Message;

class EventBus extends BusChain
{
    /**
     * @param  Message|Event $message
     * @return mixed
     */
    public function dispatch(Message $message)
    {
        if (! $message instanceof Event) {
            throw new InvalidArgumentException(
                sprintf("[EventBus] Expected object (%s). Got (%s).", Event::class, get_class($message))
            );
        }

        return parent::dispatch($message);
    }
}
