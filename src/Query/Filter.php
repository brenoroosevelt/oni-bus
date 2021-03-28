<?php
declare(strict_types=1);

namespace XBus\Query;

use XBus\KeyValueList;

class Filter
{
    use KeyValueList {
        set as private;
    }

    public function __construct(array $filters = [])
    {
        foreach ($filters as $field => $value) {
            $this->add($field, $value);
        }
    }

    public function add(string $fieldName, string $fieldValue)
    {
        $this->set($fieldName, $fieldValue);
    }
}
