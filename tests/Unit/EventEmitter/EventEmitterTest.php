<?php namespace NathanBurkett\EventDispatcher\Tests\Unit\EventEmitter;

use NathanBurkett\EventDispatcher\Event\Event;
use NathanBurkett\EventDispatcher\EventDispatcher;
use NathanBurkett\EventDispatcher\EventEmitter\EventEmitter;
use PHPUnit\Framework\TestCase;

class EventEmitterTest extends TestCase
{
    public function testEmit()
    {
        $event = $this->getMockBuilder(Event::class)
                      ->disableOriginalConstructor()
                      ->getMock();

        $dispatcher = $this->getMockBuilder(EventDispatcher::class)
                           ->disableOriginalConstructor()
                           ->getMock();

        $dispatcher->expects($this->once())->method('dispatch')->willReturn($event);

        $emitter = new EventEmitter($dispatcher);

        $this->assertEquals($event, $emitter->emit($event));
    }
}
