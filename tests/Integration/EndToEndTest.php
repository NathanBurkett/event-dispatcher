<?php namespace NathanBurkett\EventDispatcher\Tests\Integration;

use NathanBurkett\EventDispatcher\DI\ContainerReflectionResolver;
use NathanBurkett\EventDispatcher\DI\ResolverInterface;
use NathanBurkett\EventDispatcher\EventDispatcher;
use NathanBurkett\EventDispatcher\EventDispatcherInterface;
use NathanBurkett\EventDispatcher\EventEmitter\EventEmitter;
use NathanBurkett\EventDispatcher\EventEmitter\EventEmitterInterface;
use NathanBurkett\EventDispatcher\Listener\Provider\ListenerProvider;
use NathanBurkett\EventDispatcher\Listener\Provider\ListenerProviderInterface;
use NathanBurkett\EventDispatcher\Sort\Comparator\DescendingSubscriptionComparator;
use NathanBurkett\EventDispatcher\Sort\Comparator\SortingComparator;
use NathanBurkett\EventDispatcher\Sort\SortingAlgorithm;
use NathanBurkett\EventDispatcher\Sort\SortingAlgorithmInterface;
use NathanBurkett\EventDispatcher\Tests\Fixtures\UsesContainer;
use PHPUnit\Framework\TestCase;

abstract class EndToEndTest extends TestCase
{
    use UsesContainer;

    protected function setUp()
    {
        $this->propUpContainer();
    }

    protected function propUpContainer()
    {
        $this->addToContainer(TestCase::class, $this);

        $this->addToContainer(ResolverInterface::class,ContainerReflectionResolver::class)
             ->addArgument($this->container);

        $this->addToContainer(SortingComparator::class, DescendingSubscriptionComparator::class);

        $this->addToContainer(SortingAlgorithmInterface::class, SortingAlgorithm::class)
             ->addArgument(SortingComparator::class);

        $this->addToContainer(ListenerProviderInterface::class, ListenerProvider::class)
             ->addArgument(ResolverInterface::class)
             ->addArgument(SortingAlgorithmInterface::class);

        $this->addToContainer(EventDispatcherInterface::class, new EventDispatcher());

        $this->addToContainer(EventEmitterInterface::class, EventEmitter::class)
             ->addArgument(EventDispatcherInterface::class);
    }
}
