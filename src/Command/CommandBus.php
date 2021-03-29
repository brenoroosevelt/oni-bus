<?php
declare(strict_types=1);

namespace OniBus\Command;

use OniBus\BusChain;
use OniBus\Message;
use OniBus\Utility\Assert;

class CommandBus extends BusChain
{
    /**
     * @param  Message|Command $command
     * @return mixed
     */
    public function dispatch(Message $command)
    {
        Assert::instanceOf(Command::class, $command, $this);
        return parent::dispatch($command);
    }
}
