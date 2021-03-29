<?php
declare(strict_types=1);

namespace OniBus\Test\Fixture;

use OniBus\Attributes\Authorizer;
use OniBus\Attributes\CommandHandler;

class HandlerUsingAttributes
{
    #[CommandHandler]
    public function execute(GenericMessage $message): int
    {
        return 100;
    }
}
