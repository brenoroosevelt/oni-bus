<?php
declare(strict_types=1);

namespace OniBus\Test\Handler\ClassMethod\Extractor;

use Closure;
use OniBus\Attributes\EventListener;
use OniBus\Attributes\Handler;
use OniBus\Handler\ClassMethod\Extractor\ExtractorUsingAttribute;
use OniBus\Test\Fixture\ClassHandler;
use OniBus\Test\Fixture\GenericAttribute;
use OniBus\Test\TestCase;
use ReflectionException;
use ReflectionFunction;

class ExtractorUsingAttributeTest extends TestCase
{
    public function testExtractorUsingAttributeReturnEmptyWhenClassNotExists()
    {
        if (PHP_VERSION_ID < 80000) {
            $this->markTestSkipped();
            return;
        }

        $extractor = new ExtractorUsingAttribute(Handler::class, ['InvalidClass']);
        $result = $extractor->extractClassMethods();
        $this->assertEmpty($result);
    }

//    public function testaa()
//    {
//        $extractor = new MethodFirstParameterExtractor('invalid', [ClassHandler::class]);
//        $result = $extractor->extractClassMethods();
//        $this->assertEmpty($result);
//    }

//    public function testMethodFirstParameterExtractorReturnsCorrectClassMethod()
//    {
//        $extractor = new MethodFirstParameterExtractor('handle', [ClassHandler::class]);
//        $result = $extractor->extractClassMethods();
//        $this->assertEquals([
//            new ClassMethod(Message::class, ClassHandler::class, 'handle')
//        ], $result);
//    }

    public function extractHandlerAttributeProvider(): array
    {
        if (PHP_VERSION_ID < 80000) {
            return [];
        }

        return [
            'return_attribute' => [
                #[EventListener]
                function () {
                },
                EventListener::class,
                new EventListener()
            ],
            'return_attribute_when_multiples' => [
                #[EventListener]
                #[GenericAttribute]
                function () {
                },
                EventListener::class,
                new EventListener()
            ],
            'return_null_when_attr_is_not_present' => [
                function () {
                },
                EventListener::class,
                null
            ],
        ];
    }

    /**
     * @dataProvider extractHandlerAttributeProvider
     * @param Closure $fn
     * @param string $attribute
     * @param $expected
     * @throws ReflectionException
     */
    public function testExtractHandlerAttribute(Closure $fn, string $attribute, $expected)
    {
        if (PHP_VERSION_ID < 80000) {
            $this->markTestSkipped();
            return;
        }

        $function = new ReflectionFunction($fn);
        $extractor = new ExtractorUsingAttribute($attribute, [ClassHandler::class]);
        $result = $this->invokeMethod($extractor, 'extractHandlerAttribute', [$function]);
        $this->assertEquals($expected, $result);
    }
}
