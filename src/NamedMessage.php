<?php
declare(strict_types=1);

namespace OniBus;

interface NamedMessage extends Message
{
    public function getMessageName(): string;
}
