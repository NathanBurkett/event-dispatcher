<?php namespace NathanBurkett\EventDispatcher\Tests\Unit\Subscription;

use NathanBurkett\EventDispatcher\Subscription\Subscription;
use PHPUnit\Framework\TestCase;

class SubscriptionTest extends TestCase
{
    public function testInstance()
    {
        $eventClass = 'Foo';
        $listenerClass = 'Bar';

        $subscriptionA = new Subscription($eventClass, $listenerClass);

        $this->assertEquals($eventClass, $subscriptionA->getEventClass());
        $this->assertEquals($listenerClass, $subscriptionA->getListenerClass());
        $this->assertEquals(0, $subscriptionA->getPriority());

        $priority = 20;

        $subscriptionB = new Subscription($eventClass, $listenerClass, $priority);

        $this->assertEquals($eventClass, $subscriptionB->getEventClass());
        $this->assertEquals($listenerClass, $subscriptionB->getListenerClass());
        $this->assertEquals($priority, $subscriptionB->getPriority());
    }
}
