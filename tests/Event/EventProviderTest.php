<?php
declare(strict_types=1);

namespace OniBus\Test\Event;

use OniBus\Event\EventProvider;
use OniBus\Test\Fixture\GenericEvent;
use OniBus\Test\TestCase;

class EventProviderTest extends TestCase
{
    public function testShouldEventProviderRecordEvents()
    {
        $eventProvider = new EventProvider();
        $eventProvider->recordEvent(new GenericEvent(['item'=>'value']));
        $events = iterator_to_array($eventProvider->releaseEvents());
        $this->assertCount(1, $events);
        $this->assertEquals('value', $events[0]->get('item'));
    }

    public function testShouldEventProviderReleaseEvents()
    {
        $eventProvider = new EventProvider();
        $eventProvider->recordEvent(new GenericEvent(['item'=>'value']));
        $events = iterator_to_array($eventProvider->releaseEvents());
        $this->assertCount(1, $events);
        $events = iterator_to_array($eventProvider->releaseEvents());
        $this->assertCount(0, $events);
    }

    public function testShouldEventProviderAggregateEvents()
    {
        $eventProvider = new EventProvider();
        $eventProvider2 = new EventProvider();
        $eventProvider2->recordEvent(new GenericEvent(['item'=>'value']));

        $eventProvider->aggregateEventsFrom($eventProvider2);
        $events = iterator_to_array($eventProvider->releaseEvents());
        $this->assertCount(1, $events);
        $this->assertEquals('value', $events[0]->get('item'));
    }

    public function testShouldEventProviderPullEventsFrom()
    {
        $eventProvider = new EventProvider();
        $eventProvider2 = new EventProvider();
        $eventProvider2->recordEvent(new GenericEvent(['item'=>'value']));

        $eventProvider->pullEventsFrom($eventProvider2);
        $events = iterator_to_array($eventProvider->releaseEvents());
        $eventsFrom2 = iterator_to_array($eventProvider2->releaseEvents());
        $this->assertCount(1, $events);
        $this->assertCount(0, $eventsFrom2);
    }
}
