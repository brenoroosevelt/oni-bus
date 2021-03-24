<?php
declare(strict_types=1);

namespace XBus\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_METHOD)]
class CommandHandler extends Handler
{
}
