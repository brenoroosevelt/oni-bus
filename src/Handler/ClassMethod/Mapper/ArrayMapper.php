<?php
declare(strict_types=1);

namespace OniBus\Handler\ClassMethod\Mapper;

use OniBus\Handler\ClassMethod\ClassMethod;
use OniBus\Handler\ClassMethod\ClassMethodMapper;

class ArrayMapper extends DirectMapper implements ClassMethodMapper
{
    /**
     * @param array $map [['message', 'class', 'method'], ...]
     */
    public function __construct(array $map)
    {
        $mapped = array_map(function ($item) {
                list($message, $class, $method) = $item;
                return new ClassMethod($message, $class, $method);
        },
            $map);
        parent::__construct(...$mapped);
    }
}
