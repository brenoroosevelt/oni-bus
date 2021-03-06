<?php
declare(strict_types=1);

namespace OniBus\Query;

use InvalidArgumentException;
use OniBus\Payload;

class Order extends Payload
{
    const ASC = 'asc';
    const DESC = 'desc';

    protected function insert($item, $value)
    {
        $direction = mb_strtolower($value);
        if (!in_array($direction, [self::ASC, self::DESC])) {
            throw new InvalidArgumentException(
                sprintf("[OrderBy] Invalid direction (%s) for field (%s).", $direction, $item)
            );
        }

        parent::insert($item, $value);
    }

    public function add(string $fieldName, string  $direction = self::ASC): self
    {
        $new = clone $this;
        $new->insert($fieldName, $direction);
        return $new;
    }

    public static function by(string $fieldName, string $direction = self::ASC): self
    {
        return new self([$fieldName => $direction]);
    }
}
