<?php
declare(strict_types=1);

namespace OniBus\Test\Event;

use OniBus\Event\EventManager;
use OniBus\Event\EventProvider;
use OniBus\Test\Fixture\GenericEvent;
use OniBus\Test\TestCase;

class EventManagerTest extends TestCase
{
    public function testShouldGetSameInstanceOfEventProvider()
    {
        $eventProvider = EventManager::instance();
        $eventProvider2 = EventManager::instance();
        $this->assertSame($eventProvider, $eventProvider2);
        $this->assertInstanceOf(EventProvider::class, $eventProvider2);
    }

    public function testShouldEventManagerRecordAndReleaseEvents()
    {
        EventManager::recordEvent(new GenericEvent(['item'=>'value']));
        $events = iterator_to_array(EventManager::releaseEvents());
        $this->assertCount(1, $events);
        $this->assertEquals('value', $events[0]->get('item'));
    }
}
