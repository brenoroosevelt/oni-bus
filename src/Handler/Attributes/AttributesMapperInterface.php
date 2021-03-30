<?php
declare(strict_types=1);

namespace OniBus\Handler\Attributes;

use OniBus\Handler\ClassMethod\ClassMethod;

interface AttributesMapperInterface
{
    /**
     * @return ClassMethod[]
     */
    public function mapHandlers(): array;
}
