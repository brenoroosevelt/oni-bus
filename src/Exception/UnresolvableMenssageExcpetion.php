<?php
declare(strict_types=1);

namespace OniBus\Exception;

use RuntimeException;
use OniBus\Message;
use OniBus\NamedMessage;

class UnresolvableMenssageExcpetion extends RuntimeException
{
    public static function message(Message $message): self
    {
        return new self(
            sprintf(
                "Cannot resolve handler for message (%s)",
                $message instanceof NamedMessage ? $message->getMessageName() : get_class($message)
            )
        );
    }
}
