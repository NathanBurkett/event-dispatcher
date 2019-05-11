<?php namespace NathanBurkett\EventDispatcher\Tests\Integration\Listener;

use NathanBurkett\EventDispatcher\Event\Event;
use PHPUnit\Framework\TestCase;

class EventListenerTest extends TestCase
{
    public function testCallable()
    {
        $event = $this->getMockBuilder(Event::class)
                      ->disableOriginalConstructor()
                      ->getMock();

        $listener = new EventListenerDouble($this);
        $callable = $listener->getCallable();
        $callable($event);
    }
}
