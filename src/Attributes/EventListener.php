<?php
declare(strict_types=1);

namespace OniBus\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_METHOD)]
class EventListener extends Handler
{
}
