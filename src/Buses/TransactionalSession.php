<?php
declare(strict_types=1);

namespace OniBus\Buses;

interface TransactionalSession
{
    public function executeAtomically(callable $operation);
}
