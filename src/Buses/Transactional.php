<?php
declare(strict_types=1);

namespace XBus\Buses;

use XBus\Bus;
use XBus\Chain;
use XBus\ChainTrait;
use XBus\Message;

class Transactional implements Bus, Chain
{
    use ChainTrait;

    /**
     * @var TransactionalSession
     */
    protected $session;

    public function __construct(TransactionalSession $session)
    {
        $this->session = $session;
    }

    public function dispatch(Message $message)
    {
        return $this->session->executeAtomically(function () use ($message) {
            $this->next($message);
        });
    }
}
