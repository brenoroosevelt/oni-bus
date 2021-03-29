<?php
declare(strict_types=1);

namespace OniBus\Test\Fixture;

use OniBus\MessageNameFQCN;
use OniBus\NamedMessage;

class DefaultNamedMessage implements NamedMessage
{
    use MessageNameFQCN;
}
