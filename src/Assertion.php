<?php
declare(strict_types=1);

namespace XBus;

use InvalidArgumentException;

class Assertion
{
    public static function assertInstanceOf($expected, $subject, $message = null): void
    {
        if (empty($message)) {
            $message =
                sprintf(
                    "[Invalid Argument] Expected (%s). Got (%s).",
                    $expected,
                    is_object($subject) ? get_class($subject) : gettype($subject)
                );
        }

        if (!is_a($subject, $expected)) {
            throw new InvalidArgumentException($message);
        }
    }
}
