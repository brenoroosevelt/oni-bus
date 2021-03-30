<?php
declare(strict_types=1);

namespace OniBus\Test\Fixture;

use OniBus\Message;

class ClassHandler
{
    public function handle(Message $message)
    {
        return 500;
    }
}
