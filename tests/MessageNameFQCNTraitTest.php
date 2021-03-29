<?php
declare(strict_types=1);

namespace OniBus\Test;

use OniBus\MessageNameFQCN;
use OniBus\NamedMessage;
use OniBus\Test\Fixture\DefaultNamedMessage;
use PHPUnit\Framework\TestCase;

class MessageNameFQCNTraitTest extends TestCase
{
    public function testShouldTraitImplementsNamedMessage()
    {
        $instance = new class implements NamedMessage {
            use MessageNameFQCN;
        };
        $this->assertInstanceOf(NamedMessage::class, $instance);
    }

    public function testShouldTraitReturnCorrectClassName()
    {
        $instance = new DefaultNamedMessage();
        $this->assertEquals(DefaultNamedMessage::class, $instance->getMessageName());
    }
}
