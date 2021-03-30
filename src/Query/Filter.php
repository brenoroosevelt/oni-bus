<?php
declare(strict_types=1);

namespace OniBus\Query;

use OniBus\Payload;

class Filter extends Payload
{
    public function __construct(array $filters = [])
    {
        parent::__construct($filters);
    }

    public function add(string $fieldName, string $fieldValue)
    {
        $this->set($fieldName, $fieldValue);
    }
}
