<?php
declare(strict_types=1);

namespace OniBus\Handler\ClassMethod\Extractor;

use OniBus\Handler\ClassMethod\ClassMethod;
use OniBus\Handler\ClassMethod\ClassMethodExtractor;
use ReflectionAttribute;
use ReflectionClass;
use ReflectionException;
use ReflectionFunctionAbstract;
use ReflectionMethod;
use OniBus\Attributes\Handler;
use ReflectionNamedType;
use RuntimeException;
use function Sodium\version_string;

class ExtractorUsingAttribute implements ClassMethodExtractor
{
    /**
     * @var string
     */
    protected $attribute;

    /**
     * @var array
     */
    protected $handlersFQCN;

    public function __construct(string $attribute, array $handlersFQCN = [])
    {
        $this->assertAttributesAvailable();
        $this->attribute = $attribute;
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
            $attribute = $this->extractHandlerAttribute($method);
            if (empty($attribute)) {
                continue;
            }

            $handlers[] = new ClassMethod($this->extractMessage($method, $attribute), $class, $method->getName());
        }

        return $handlers;
    }

    protected function extractHandlerAttribute(ReflectionFunctionAbstract $method): ?Handler
    {
        $attribute = $method->getAttributes($this->attribute, ReflectionAttribute::IS_INSTANCEOF)[0] ?? null;
        if ($attribute instanceof ReflectionAttribute) {
            $attr =  $attribute->newInstance();
            assert($attr instanceof Handler);
            return $attr;
        }

        return null;
    }

    protected function extractMessage(ReflectionMethod $method, Handler $attribute): string
    {
        if (!empty($attribute->getMessage())) {
            return  $attribute->getMessage();
        }

        $hasParameters = (bool) $method->getNumberOfParameters();
        if ($hasParameters) {
            $paramType = $method->getParameters()[0]->getType();
            if ($paramType instanceof ReflectionNamedType && !$paramType->isBuiltin()) {
                return $paramType->getName();
            }
        }

        throw new RuntimeException(
            sprintf(
                "Invalid Handler Attribute: No 'Message' has been set in %s::%s",
                $method->getDeclaringClass()->getName(),
                $method->getName()
            )
        );
    }

    public function attributesAvailable(): bool
    {
        return PHP_VERSION_ID >= 80000;
    }

    public function assertAttributesAvailable():void
    {
        if (!$this->attributesAvailable()) {
            throw new RuntimeException(
                sprintf("Attributes are not available. Use PHP version >= 8.0")
            );
        }
    }
}
