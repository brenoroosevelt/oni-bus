<?php
declare(strict_types=1);

namespace OniBus\Query;

use OniBus\BusChain;
use OniBus\Message;
use OniBus\Utility\Assert;

class QueryBus extends BusChain
{
    /**
     * @param  Message|Query $query
     * @return mixed
     */
    public function dispatch(Message $query)
    {
        Assert::instanceOf(Query::class, $query, $this);
        return parent::dispatch($query);
    }
}
