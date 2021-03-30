<?php
declare(strict_types=1);

namespace OniBus\Handler\ClassMethod\Mapper;

use OniBus\Handler\ClassMethod\ClassMethod;
use OniBus\Handler\ClassMethod\ClassMethodMapper;

class DefaultMethodMapper extends DirectMapper implements ClassMethodMapper
{
    /**
     * @param array $map ['message' => 'class', ...]
     * @param string $method default method
     */
    public function __construct(array $map, string $method = "__invoke")
    {
        $mapped = [];
        foreach ($map as $message => $class) {
            if (method_exists($class, $method)) {
                $mapped[] = new ClassMethod($message, $class, $method);
            }
        }

        parent::__construct(...$mapped);
    }
}
