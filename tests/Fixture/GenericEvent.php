<?php
declare(strict_types=1);

namespace OniBus\Test\Fixture;

use OniBus\Event\Event;
use OniBus\Payload;

class GenericEvent extends Payload implements Event
{
}
