<?php
declare(strict_types=1);

namespace XBus;

interface Bus
{
    public function dispatch(Message $message);
}
