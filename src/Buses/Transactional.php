<?php
declare(strict_types=1);

namespace OniBus\Buses;

use OniBus\Chain;
use OniBus\ChainTrait;
use OniBus\Message;

class Transactional implements Chain
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
