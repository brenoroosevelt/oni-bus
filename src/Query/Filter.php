<?php
declare(strict_types=1);

namespace OniBus\Query;

use OniBus\Payload;

class Filter extends Payload
{
    public function add(string $fieldName, string $fieldValue): self
    {
        $new = clone $this;
        $new->set($fieldName, $fieldValue);
        return $new;
    }
}
