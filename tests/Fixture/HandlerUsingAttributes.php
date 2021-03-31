<?php
declare(strict_types=1);

namespace OniBus\Test\Fixture;

use OniBus\Attributes\CommandHandler;
use OniBus\Attributes\QueryHandler;

class HandlerUsingAttributes
{
    #[CommandHandler]
    public function execute(GenericMessage $message): int
    {
        return 100;
    }

    #[QueryHandler(GenericMessage::class)]
    public function fetch($message)
    {
    }

    public function __invoke(GenericMessage $event): int
    {
        return 200;
    }
}
