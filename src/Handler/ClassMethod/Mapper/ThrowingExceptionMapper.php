<?php
declare(strict_types=1);

namespace OniBus\Handler\ClassMethod\Mapper;

use OniBus\Exception\UnresolvableMessageException;
use OniBus\Handler\ClassMethod\ClassMethodMapper;
use OniBus\Message;

/**
 * Decorator
 */
class ThrowingExceptionMapper implements ClassMethodMapper
{
    /**
     * @var ClassMethodMapper
     */
    private $mapper;

    public function __construct(ClassMethodMapper $mapper)
    {
        $this->mapper = $mapper;
    }

    /**
     * @inheritDoc
     */
    public function map(Message $message): array
    {
        $mapped = $this->mapper->map($message);
        if (empty($mapped)) {
            throw UnresolvableMessageException::message($message);
        }

        return $mapped;
    }
}
