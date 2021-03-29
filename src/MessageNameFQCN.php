<?php
declare(strict_types=1);

namespace OniBus;

/**
 * Naming provider for Message based on Fully Qualified Class Name
 */
trait MessageNameFQCN
{
    public function getMessageName(): string
    {
        return get_class($this);
    }
}
