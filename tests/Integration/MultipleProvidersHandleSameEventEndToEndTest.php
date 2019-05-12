<?php namespace NathanBurkett\EventDispatcher\Tests\Integration;

use NathanBurkett\EventDispatcher\EventDispatcherInterface;
use NathanBurkett\EventDispatcher\EventEmitter\EventEmitterInterface;
use NathanBurkett\EventDispatcher\Listener\Provider\ListenerProviderInterface;
use NathanBurkett\EventDispatcher\Subscription\Subscription;
use NathanBurkett\EventDispatcher\Tests\Integration\Concrete\Event\BetaEvent;
use NathanBurkett\EventDispatcher\Tests\Integration\Concrete\Event\TrackingEvent;
use NathanBurkett\EventDispatcher\Tests\Integration\Concrete\Listener\RequiredListener;
use NathanBurkett\EventDispatcher\Tests\Integration\Concrete\Listener\UnreachableEventListener;

class MultipleProvidersHandleSameEventEndToEndTest extends EndToEndTest
{
    public function test()
    {
        /** @var ListenerProviderInterface $providerA */
        $providerA = $this->getContainer()->get(ListenerProviderInterface::class);

        $providerA->addSubscription(
            new Subscription(TrackingEvent::class, RequiredListener::class)
        );

        /** @var ListenerProviderInterface $providerB */
        $providerB = $this->getContainer()->get(ListenerProviderInterface::class);

        $providerB->addSubscription(
            new Subscription(TrackingEvent::class, RequiredListener::class)
        );
        $providerB->addSubscription(
            new Subscription(BetaEvent::class, UnreachableEventListener::class)
        );

        /** @var EventDispatcherInterface $dispatcher */
        $dispatcher = $this->getContainer()->get(EventDispatcherInterface::class);
        $dispatcher->addListenerProvider($providerA);
        $dispatcher->addListenerProvider($providerB);

        $event = new TrackingEvent();

        $this->getContainer()
             ->get(EventEmitterInterface::class)
             ->emit($event);

        $this->assertEquals([
            RequiredListener::class,
            RequiredListener::class,
        ], $event->getListenersSeen());

        $this->assertFalse($event->isPropagationStopped());
    }
}
