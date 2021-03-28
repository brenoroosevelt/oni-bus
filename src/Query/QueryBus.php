<?php
declare(strict_types=1);

namespace XBus\Query;

use XBus\Assertion;
use XBus\BusChain;
use XBus\Message;

class QueryBus extends BusChain
{
    /**
     * @param  Message|Query $message
     * @return mixed
     */
    public function dispatch(Message $message)
    {
        Assertion::assertInstanceOf(Query::class, $message);
        return parent::dispatch($message);
    }
}
