<?php namespace NathanBurkett\EventDispatcher\Tests\Integration\Listener\Provider;

use NathanBurkett\EventDispatcher\DI\ResolverInterface;
use NathanBurkett\EventDispatcher\Event\Event;
use NathanBurkett\EventDispatcher\Listener\Provider\ListenerProvider;
use NathanBurkett\EventDispatcher\Sort\SortingAlgorithmInterface;
use NathanBurkett\EventDispatcher\Subscription\Subscription;
use NathanBurkett\EventDispatcher\Tests\Integration\Listener\EventListenerDouble;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class ListenerProviderTest extends TestCase
{
    public function testAddListener()
    {
        $subscriptionA = new Subscription('foo', 'bar');
        $subscriptionB = new Subscription('foo', 'baz');
        $subscriptionC = new Subscription('bar', 'baz');

        $provider = new ListenerProvider(
            $this->getResolver(),
            $this->getSortingAlgorithm(),
            $subscriptionA,
            $subscriptionB,
            $subscriptionC
        );

        $this->assertEquals(['foo', 'bar'], $provider->getEvents());
        $this->assertEquals([
            'foo' => [
                'bar' => $subscriptionA,
                'baz' => $subscriptionB,
            ],
            'bar' => [
                'baz' => $subscriptionC
            ]
        ], $provider->getSubscriptions());
    }

    public function testAddListenerWithSameEventAndListenerWillOverwrite()
    {
        $subscriptionA = new Subscription('foo', 'bar');
        $subscriptionB = new Subscription('foo', 'bar');

        $provider = new ListenerProvider(
            $this->getResolver(),
            $this->getSortingAlgorithm(),
            $subscriptionA,
            $subscriptionB
        );

        $this->assertEquals(['foo'], $provider->getEvents());
        $this->assertEquals([
            'foo' => [
                'bar' => $subscriptionB
            ]
        ], $provider->getSubscriptions());
    }

    public function testGetListenersForEvent()
    {
        $event = new Event();

        $listenerA = $this->getMockBuilder(EventListenerDouble::class)
                          ->setMockClassName('Foo')
                          ->setConstructorArgs([$this])
                          ->getMock();

        $listenerB = $this->getMockBuilder(EventListenerDouble::class)
                          ->setMockClassName('Bar')
                          ->setConstructorArgs([$this])
                          ->getMock();

        $subscriptionA = new Subscription(Event::class, 'Foo');
        $subscriptionB = new Subscription(Event::class, 'Bar');

        $sorter = $this->getSortingAlgorithm();
        $sorter->expects(self::once())->method('sort')->willReturn([$subscriptionA, $subscriptionB]);

        $resolver = $this->getResolver();
        $resolver->expects(self::exactly(2))->method('resolve')->willReturn($listenerA, $listenerB);

        $provider = new ListenerProvider($resolver, $sorter, $subscriptionA, $subscriptionB);

        foreach ($provider->getListenersForEvent($event) as $callable) {
            $callable($event);
        }
    }

    public function testGetListenersForEventWhenDoesNotHaveEvent()
    {
        $event = new Event();

        $subscription = new Subscription('Foo', EventListenerDouble::class);

        $sorter = $this->getSortingAlgorithm();
        $sorter->expects(self::never())->method('sort');

        $resolver = $this->getResolver();
        $resolver->expects(self::never())->method('resolve');

        $provider = new ListenerProvider($resolver, $sorter, $subscription);

        foreach ($provider->getListenersForEvent($event) as $callable) {
            $this->fail('Expected no callables');
        }
    }

    /**
     * @return MockObject|ResolverInterface
     */
    protected function getResolver(): ResolverInterface
    {
        return $this->getMockBuilder(ResolverInterface::class)
                    ->disableOriginalConstructor()
                    ->getMock();
    }

    /**
     * @return MockObject|SortingAlgorithmInterface
     */
    protected function getSortingAlgorithm(): SortingAlgorithmInterface
    {
        return $this->getMockBuilder(SortingAlgorithmInterface::class)
                    ->disableOriginalConstructor()
                    ->getMock();
    }
}
