<?php
declare(strict_types=1);

namespace OniBus\Utility;

use InvalidArgumentException;
use OniBus\Exception\RequiredParameterMissingException;

class Assert
{
    public static function instanceOf(string $expected, $subject, $where)
    {
        if (!is_a($subject, $expected)) {
            throw new InvalidArgumentException(
                sprintf(
                    "[%s] Expected an instance of (%s). Got (%s).",
                    self::getType($where),
                    self::getType($subject),
                    self::getType($expected)
                )
            );
        }
    }

    public static function requiredParameters(array $required, array $parameters, $where)
    {
        $missing = array_filter(
            $required,
            function ($item) use ($parameters) {
                return !array_key_exists($item, $parameters);
            }
        );

        if (!empty($missing)) {
            throw new RequiredParameterMissingException($where, $missing);
        }
    }

    protected static function getType($subject): string
    {
        return is_object($subject) ? get_class($subject) : gettype($subject);
    }
}
