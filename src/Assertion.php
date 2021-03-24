<?php
declare(strict_types=1);

namespace XBus;

use InvalidArgumentException;

class Assertion
{
    public static function assertInstanceOf($expected, $subject): void
    {
        if (!is_a($subject, $expected)) {
            throw new InvalidArgumentException(
                sprintf(
                    "[Invalid Argument] Expected (%s). Got (%s).",
                    $expected,
                    is_object($subject) ? get_class($subject) : gettype($subject)
                )
            );
        }
    }
}
