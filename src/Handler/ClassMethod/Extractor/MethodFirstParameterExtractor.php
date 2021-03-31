<?php
declare(strict_types=1);

namespace OniBus\Handler\ClassMethod\Extractor;

use OniBus\Handler\ClassMethod\ClassMethod;
use OniBus\Handler\ClassMethod\ClassMethodExtractor;
use ReflectionClass;
use ReflectionException;
use ReflectionFunctionAbstract;
use ReflectionMethod;
use ReflectionNamedType;

class MethodFirstParameterExtractor implements ClassMethodExtractor
{
    /**
     * @var string
     */
    protected $method;

    /**
     * @var array
     */
    protected $handlersFQCN;

    public function __construct(string $method, array $handlersFQCN = [])
    {
        $this->method = $method;
        $this->handlersFQCN = $handlersFQCN;
    }

    /**
     * @inheritDoc
     */
    public function extractClassMethods(): array
    {
        $mapped = [];
        foreach ($this->handlersFQCN as $class) {
            $mapped = array_merge($mapped, $this->getHandlers($class));
        }

        return $mapped;
    }

    /**
     * @param  string $class
     * @return ClassMethod[]
     * @throws ReflectionException
     */
    protected function getHandlers(string $class): array
    {
        $handlers = [];
        if (!class_exists($class)) {
            return $handlers;
        }

        $reflectionClass = new ReflectionClass($class);
        foreach ($reflectionClass->getMethods() as $method) {
            if ($method->getName() !== $this->method) {
                continue;
            }

            $firstParameterTypeHint = $this->firstParameterTypeHint($method);
            if (!is_null($firstParameterTypeHint)) {
                $handlers[] = new ClassMethod($firstParameterTypeHint, $class, $method->getName());
            }
        }

        return $handlers;
    }

    public function firstParameterTypeHint(ReflectionFunctionAbstract $method): ?string
    {
        if (!$method->getNumberOfParameters()) {
            return null;
        }

        $parameterType = $method->getParameters()[0]->getType();
        if (! $parameterType instanceof ReflectionNamedType) {
            return null;
        }

        if ($parameterType->isBuiltin()) {
            return null;
        }

        return ltrim($parameterType->getName(), "?");
    }
}
