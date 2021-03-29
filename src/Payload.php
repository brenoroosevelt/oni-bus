<?php
declare(strict_types=1);

namespace OniBus;

use InvalidArgumentException;
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

    protected function assertRequiredParameters(array $required): void
    {
        $missing = array_filter($required, function ($item) {
                return !$this->has($item);
            }
        );

        if (!empty($missing)) {
            throw new InvalidArgumentException(
                sprintf(
                    "Required parameters (%s) is missing for (%s).",
                    implode(',', $missing),
                    get_class($this)
                )
            );
        }
    }
}
