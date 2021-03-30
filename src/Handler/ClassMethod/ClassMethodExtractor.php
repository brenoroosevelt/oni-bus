<?php
declare(strict_types=1);

namespace OniBus\Handler\ClassMethod;

interface ClassMethodExtractor
{
    /**
     * @return ClassMethod[]
     */
    public function extractClassMethods(): array;
}
