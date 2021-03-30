<?php
declare(strict_types=1);

namespace OniBus\Handler\ClassMethod\Mapper;

use OniBus\Handler\ClassMethod\ClassMethod;
use OniBus\Handler\ClassMethod\ClassMethodMapper;
use OniBus\Message;
use OniBus\NamedMessage;

class DirectMapper implements ClassMethodMapper
{
    /**
     * @var ClassMethod[]
     */
    protected $mapped = [];

    public function __construct(ClassMethod ...$mapped)
    {
        $this->mapped = $mapped;
    }

    /**
     * @inheritDoc
     */
    public function map(Message $message): array
    {
        $messageName = $message instanceof NamedMessage ? $message->getMessageName() : get_class($message);
        $filtered = [];
        foreach ($this->mapped as $classMethod) {
            if ($classMethod->message() === $messageName) {
                $filtered[] = $classMethod;
            }
        }

        return $filtered;
    }
}
