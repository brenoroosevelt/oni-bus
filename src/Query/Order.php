<?php
declare(strict_types=1);

namespace OniBus\Query;

use InvalidArgumentException;
use OniBus\Utility\KeyValueList;

class Order
{
    const ASC = 'asc';
    const DESC = 'desc';

    use KeyValueList {
        set as private;
        delete as private;
    }

    public function __construct(array $orders = [])
    {
        foreach ($orders as $fieldName => $direction) {
            $this->add($fieldName, $direction);
        }
    }

    public function add(string $fieldName, string  $direction = self::ASC)
    {
        $direction = mb_strtolower($direction);
        if (!in_array($direction, [self::ASC, self::DESC])) {
            throw new InvalidArgumentException(
                sprintf("[OrderBy] Invalid direction (%s) for field (%s).", $direction, $fieldName)
            );
        }

        $this->set($fieldName, $direction);
    }

    public static function by(string $fieldName, string $direction = self::ASC): self
    {
        return new self([$fieldName => $direction]);
    }
}
