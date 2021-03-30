<?php
declare(strict_types=1);

namespace OniBus\Query;

use OniBus\Payload;

class Filter extends Payload
{
    public function add(string $fieldName, string $fieldValue)
    {
        $this->set($fieldName, $fieldValue);
    }
}
