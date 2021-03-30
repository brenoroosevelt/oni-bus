<?php
declare(strict_types=1);

namespace OniBus\Handler\ClassMethod\Mapper;

use OniBus\Handler\Attributes\AttributesMapperInterface;
use OniBus\Handler\ClassMethod\ClassMethodMapper;

class AttributesMapper extends DirectMapper implements ClassMethodMapper
{
    public function __construct(AttributesMapperInterface $attributesMapper)
    {
        parent::__construct(...$attributesMapper->mapHandlers());
    }
}
