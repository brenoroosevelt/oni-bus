<?php
declare(strict_types=1);

namespace XBus;

interface NamedMessage extends Message
{
    public function getMessageName(): string;
}
