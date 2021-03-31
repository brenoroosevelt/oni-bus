<?php
declare(strict_types=1);

namespace OniBus\Test\Fixture;

use OniBus\Attributes\CommandHandler;

class HandlerWithInvalidAttribute
{
    #[CommandHandler]
    public function execute($message)
    {
    }
}
