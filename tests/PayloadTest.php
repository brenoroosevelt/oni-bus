<?php
declare(strict_types=1);

namespace OniBus\Test;

use LogicException;
use OniBus\Exception\RequiredParameterMissingException;
use OniBus\Payload;

class PayloadTest extends TestCase
{
    public function testShouldCreateAPayloadWithSomeValuesAndGetThem()
    {
        $payload = new Payload(['param1' => 1, 'param2'=> 'str']);
        $this->assertTrue($payload->has('param1'));
        $this->assertTrue($payload->has('param2'));
        $this->assertEquals(1, $payload->get('param1'));
        $this->assertEquals('str', $payload->get('param2'));
    }

    public function testShouldCreateAPayloadAndConvertToArray()
    {
        $payload = new Payload(['param1' => 1, 'param2'=> 'str']);
        $this->assertEquals(['param1' => 1, 'param2'=> 'str'], $payload->toArray());
    }

    public function testShouldCreateAPayloadAndGetJsonSerialize()
    {
        $payload = new Payload(['param1' => 1, 'param2'=> 'str']);
        $this->assertEquals(
            ['param1' => 1, 'param2'=> 'str'],
            (array) json_decode(json_encode($payload->jsonSerialize()))
        );
    }

    public function testShouldCreateAPayloadAndGetValuesFromMagicProperty()
    {
        $payload = new Payload(['param1' => 1, 'param2'=> 'str']);
        $this->assertEquals(1, $payload->param1);
        $this->assertEquals('str', $payload->param2);
    }

    public function testShouldCreateAPayloadAndGetValuesFromMagicMethod()
    {
        $payload = new Payload(['param1' => 1, 'param2'=> 'str']);
        $this->assertEquals(1, $payload->param1());
        $this->assertEquals('str', $payload->param2());
    }

    public function testShouldPayloadThrowsExceptionsWhenMethodDoesNotExists()
    {
        $payload = new Payload(['param1' => 1]);
        $this->expectException(LogicException::class);
        $this->assertEquals(1, $payload->param2());
    }

    public function testShouldPayloadThrowsExceptionsWhenPropertyDoesNotExists()
    {
        $payload = new Payload(['param1' => 1]);
        $this->expectException(LogicException::class);
        $this->assertEquals(1, $payload->param2);
    }

    public function testShouldCreateAPayloadValidateRequiredParams()
    {
        $payload = new Payload(['param1' => 1, 'param2'=> 'str']);
        $this->expectException(RequiredParameterMissingException::class);
        $this->invokeMethod($payload, 'assertRequiredParameters', [['param1', 'param3']]);
    }
}
