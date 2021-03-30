<?php
declare(strict_types=1);

namespace OniBus\Handler\Attributes;

use OniBus\Handler\ClassMethod\ClassMethod;
use ReflectionAttribute;
use ReflectionClass;
use ReflectionException;
use ReflectionMethod;
use OniBus\Attributes\Handler;
use RuntimeException;

class AttributesMapper implements AttributesMapperInterface
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
        $this->attribute = $attribute;
        $this->handlersFQCN = $handlersFQCN;
    }

    /**
     * @inheritDoc
     */
    public function mapHandlers(): array
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

    protected function extractHandlerAttribute(ReflectionMethod $method): ?Handler
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
        $hasParameters = (bool) $method->getNumberOfParameters();
        if (empty($attribute->getMessage()) && !$hasParameters) {
            throw new RuntimeException(
                sprintf(
                    "Invalid Handler: No 'Message' has been set in %s::%s",
                    $method->getDeclaringClass()->getName(),
                    $method->getName()
                )
            );
        }

        return $attribute->getMessage() ?? (string) $method->getParameters()[0]->getType();
    }
}
