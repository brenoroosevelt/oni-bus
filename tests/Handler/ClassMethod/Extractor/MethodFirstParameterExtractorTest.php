<?php
declare(strict_types=1);

namespace OniBus\Test\Handler\ClassMethod\Extractor;

use Closure;
use OniBus\Handler\ClassMethod\ClassMethod;
use OniBus\Handler\ClassMethod\Extractor\MethodFirstParameterExtractor;
use OniBus\Message;
use OniBus\Test\Fixture\ClassHandler;
use OniBus\Test\Fixture\GenericMessage;
use OniBus\Test\TestCase;
use ReflectionException;
use ReflectionFunction;

class MethodFirstParameterExtractorTest extends TestCase
{
    public function testMethodFirstParameterExtractorReturnEmptyWhenClassNotExists()
    {
        $extractor = new MethodFirstParameterExtractor('any', ['InvalidClass']);
        $result = $extractor->extractClassMethods();
        $this->assertEmpty($result);
    }

    public function testMethodFirstParameterExtractorReturnEmptyWhenMethodNotExists()
    {
        $extractor = new MethodFirstParameterExtractor('invalid', [ClassHandler::class]);
        $result = $extractor->extractClassMethods();
        $this->assertEmpty($result);
    }

    public function testMethodFirstParameterExtractorReturnsCorrectClassMethod()
    {
        $extractor = new MethodFirstParameterExtractor('handle', [ClassHandler::class]);
        $result = $extractor->extractClassMethods();
        $this->assertEquals([
            new ClassMethod(Message::class, ClassHandler::class, 'handle')
        ], $result);
    }

    public function methodFirstParameterExtractorFirstParameterTypeHintProvider(): array
    {
        return [
            'empty_parameters_return_null' => [
                function () {
                },
                null
            ],
            'primitive_parameters_return_null' => [
                function (int $a) {
                },
                null
            ],
            'undefined_type_hint_parameters_return_null' => [
                function ($a) {
                },
                null
            ],
            'typed_parameter_return_type' => [
                function (GenericMessage $genericMessage) {
                },
                GenericMessage::class
            ],
            'typed__nullable_parameter_return_type' => [
                function (?GenericMessage $genericMessage) {
                },
                GenericMessage::class
            ],
        ];
    }

    /**
     * @dataProvider methodFirstParameterExtractorFirstParameterTypeHintProvider
     * @param Closure $fn
     * @param $expected
     * @throws ReflectionException
     */
    public function testMethodFirstParameterExtractorFirstParameterTypeHint(Closure $fn, $expected)
    {
        $function = new ReflectionFunction($fn);
        $extractor = new MethodFirstParameterExtractor('any', [ClassHandler::class]);
        $result = $this->invokeMethod($extractor, 'firstParameterTypeHint', [$function]);
        $this->assertEquals($expected, $result);
    }
}
