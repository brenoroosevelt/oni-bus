<?php
declare(strict_types=1);

namespace XBus\Query;

use Exception;

class Order
{
    const ASC = 'asc';
    const DESC = 'desc';

    use KeyValueList {
        set as private;
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
            throw new Exception(
                sprintf("Invalid direction (%s).", $direction)
            );
        }

        $this->set($fieldName, $direction);
    }

    public static function by(string $fieldName, string $direction): Order
    {
        return new self([$fieldName => $direction]);
    }
}
