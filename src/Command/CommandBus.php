<?php
declare(strict_types=1);

namespace XBus\Command;

use XBus\Assertion;
use XBus\BusChain;
use XBus\Message;

class CommandBus extends BusChain
{
    /**
     * @param  Message|Command $message
     * @return mixed
     */
    public function dispatch(Message $message)
    {
        Assertion::assertInstanceOf(
            Command::class,
            $message,
            sprintf("[CommandBus] Expected object (%s). Got (%s).", Command::class, get_class($message))
        );

        return parent::dispatch($message);
    }
}
