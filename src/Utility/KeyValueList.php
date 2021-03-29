<?php
declare(strict_types=1);

namespace OniBus\Utility;

use Generator;
use LogicException;

trait KeyValueList
{
    /**
     * @var array
     */
    protected $elements = [];

    public function set($id, $value): void
    {
        $this->elements[$id] = $value;
    }

    public function get($id)
    {
        if (!$this->has($id)) {
            throw new LogicException(
                sprintf("[%s] Item not found (%s).", get_class($this), $id)
            );
        }

        return $this->elements[$id];
    }

    public function has($id): bool
    {
        return array_key_exists($id, $this->elements);
    }

    public function delete($id): void
    {
        unset($this->elements[$id]);
    }

    public function getIterator(): Generator
    {
        foreach ($this->elements as $id => $element) {
            yield $id => $element;
        }
    }

    public function count(): int
    {
        return count($this->elements);
    }

    public function isEmpty(): bool
    {
        return $this->count() == 0;
    }

    public function toArray(): array
    {
        return $this->elements;
    }
}
