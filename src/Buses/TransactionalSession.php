<?php
declare(strict_types=1);

namespace XBus\Buses;

interface TransactionalSession
{
    public function executeAtomically(callable $operation);
}
