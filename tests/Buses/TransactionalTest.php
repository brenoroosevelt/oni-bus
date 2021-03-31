<?php
declare(strict_types=1);

namespace OniBus\Test\Buses;

use Exception;
use LogicException;
use OniBus\Bus;
use OniBus\Buses\Transactional;
use OniBus\Buses\TransactionalSession;
use OniBus\Message;
use OniBus\Test\Fixture\GenericMessage;
use OniBus\Test\TestCase;

class TransactionalTest extends TestCase
{
    public function testTransactionalSessionCommit()
    {
        $dummySession = new class implements TransactionalSession {

            public $execution = [];

            public function executeAtomically(callable $operation)
            {
                try {
                    $this->execution[] = 'begin';
                    $result = $operation();
                    $this->execution[] = 'commit';
                    return $result;
                } catch (Exception $exception) {
                    $this->execution[] = 'rollback';
                    return null;
                }
            }
        };

        $nextBus = new class implements Bus {
            public function dispatch(Message $message)
            {
            }
        };

        $transactional = new Transactional($dummySession);
        $transactional->setNext($nextBus);
        $transactional->dispatch(new GenericMessage());
        $this->assertEquals([
            'begin',
            'commit'
        ], $dummySession->execution);
    }

    public function testTransactionalSessionRollback()
    {
        $dummySession = new class implements TransactionalSession {

            public $execution = [];

            public function executeAtomically(callable $operation)
            {
                try {
                    $this->execution[] = 'begin';
                    $result = $operation();
                    $this->execution[] = 'commit';
                    return $result;
                } catch (LogicException $exception) {
                    $this->execution[] = 'rollback';
                    return null;
                }
            }
        };

        $nextBus = new class implements Bus {
            public function dispatch(Message $message)
            {
                throw new LogicException('err');
            }
        };

        $transactional = new Transactional($dummySession);
        $transactional->setNext($nextBus);
        $transactional->dispatch(new GenericMessage());
        $this->assertEquals([
            'begin',
            'rollback'
        ], $dummySession->execution);
    }
}
