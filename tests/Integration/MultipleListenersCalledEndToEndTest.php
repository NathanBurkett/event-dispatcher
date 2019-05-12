<?php namespace NathanBurkett\EventDispatcher\Tests\Integration;

use NathanBurkett\EventDispatcher\EventDispatcherInterface;
use NathanBurkett\EventDispatcher\EventEmitter\EventEmitterInterface;
use NathanBurkett\EventDispatcher\Listener\Provider\ListenerProviderInterface;
use NathanBurkett\EventDispatcher\Subscription\Subscription;
use NathanBurkett\EventDispatcher\Tests\Integration\Concrete\Event\TrackingEvent;
use NathanBurkett\EventDispatcher\Tests\Integration\Concrete\Listener\RequiredListener;
use NathanBurkett\EventDispatcher\Tests\Integration\Concrete\Listener\SuccessfulOnLastAttemptRetryableListener;

class MultipleListenersCalledEndToEndTest extends EndToEndTest
{
    public function test()
    {
        /** @var ListenerProviderInterface $provider */
        $provider = $this->getContainer()->get(ListenerProviderInterface::class);

        $provider->addSubscription(
            new Subscription(TrackingEvent::class, RequiredListener::class)
        );
        $provider->addSubscription(
            new Subscription(TrackingEvent::class, SuccessfulOnLastAttemptRetryableListener::class)
        );

        $this->getContainer()
             ->get(EventDispatcherInterface::class)
             ->addListenerProvider($provider);

        $event = new TrackingEvent();

        $this->getContainer()
             ->get(EventEmitterInterface::class)
             ->emit($event);

        $this->assertEquals([
            RequiredListener::class,
            SuccessfulOnLastAttemptRetryableListener::class,
        ], $event->getListenersSeen());

        $this->assertFalse($event->isPropagationStopped());
    }
}
