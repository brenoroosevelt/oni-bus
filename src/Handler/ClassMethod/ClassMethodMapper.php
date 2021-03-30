<?php
declare(strict_types=1);

namespace OniBus\Handler\ClassMethod;

use OniBus\Message;

interface ClassMethodMapper
{
    /**
     * @param Message $message
     * @return ClassMethod[]
     */
    public function map(Message $message): array;
}
