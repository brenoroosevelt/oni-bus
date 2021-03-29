<?php
declare(strict_types=1);

namespace OniBus;

use OniBus\Utility\KeyValueList;

class Payload implements Message
{
    use KeyValueList {
        set as protected;
        delete as protected;
    }

    public function __construct(array $data)
    {
        foreach ($data as $item => $value) {
            $this->set($item, $value);
        }
    }
}
