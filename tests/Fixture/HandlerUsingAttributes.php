<?php
declare(strict_types=1);

namespace XBus\Test\Fixture;

use XBus\Attributes\Authorizer;
use XBus\Attributes\CommandHandler;

class HandlerUsingAttributes
{
    #[CommandHandler]
    public function execute(GenericMessage $message): int
    {
        return 100;
    }
}
