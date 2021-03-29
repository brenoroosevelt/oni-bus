<?php
declare(strict_types=1);

namespace OniBus;

use InvalidArgumentException;
use JsonSerializable;
use OniBus\Exception\RequiredParameterMissingException;
use OniBus\Utility\KeyValueList;

class Payload implements JsonSerializable
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
        });

        if (!empty($missing)) {
            throw new RequiredParameterMissingException($this, $missing);
        }
    }

    public function __get($name)
    {
        return $this->get($name);
    }

    public function __call($name, $arguments)
    {
        return $this->get($name);
    }

    public function jsonSerialize(): array
    {
        return $this->toArray();
    }
}
