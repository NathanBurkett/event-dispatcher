<?php namespace NathanBurkett\EventDispatcher\Tests\Unit\Event;

use NathanBurkett\EventDispatcher\Event\Event;
use PHPUnit\Framework\TestCase;

class EventTest extends TestCase
{
    public function testPropagationStoppage()
    {
        $event = new Event();
        $this->assertFalse($event->isPropagationStopped());

        $event->stopPropagation();
        $this->assertTrue($event->isPropagationStopped());
    }
}
