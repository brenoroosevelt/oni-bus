<?php
declare(strict_types=1);

namespace OniBus\Test\Fixture;

use OniBus\Event\Event;
use OniBus\Payload;
use function \get_defined_vars;

/**
 * @method int user_id()
 * @method string name()
 */
class UserCreatedEvent extends Payload implements Event
{
    public function __construct(int $user_id, string $name)
    {
        parent::__construct(get_defined_vars());
    }
}
