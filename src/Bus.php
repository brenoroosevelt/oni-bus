<?php
declare(strict_types=1);

namespace OniBus;

interface Bus
{
    public function dispatch(Message $message);
}
