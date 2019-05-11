<?php namespace NathanBurkett\EventDispatcher\Listener\Provider;

use NathanBurkett\EventDispatcher\DI\ResolverInterface;
use NathanBurkett\EventDispatcher\Listener\ListenerInterface;
use NathanBurkett\EventDispatcher\Subscription\SubscriptionInterface;
use NathanBurkett\EventDispatcher\Sort\SortingAlgorithmInterface;

class ListenerProvider implements ListenerProviderInterface
{
    /**
     * @var ResolverInterface
     */
    protected $resolver;

    /**
     * @var SortingAlgorithmInterface
     */
    protected $sortingAlgorithm;

    /**
     * @var array[]
     */
    protected $subscriptions = [];

    /**
     * @param ResolverInterface $resolver
     * @param SortingAlgorithmInterface $sortingAlgorithm
     * @param SubscriptionInterface ...$subscriptions
     */
    public function __construct(
        ResolverInterface $resolver,
        SortingAlgorithmInterface $sortingAlgorithm,
        SubscriptionInterface ...$subscriptions
    ) {
        $this->resolver = $resolver;
        $this->sortingAlgorithm = $sortingAlgorithm;

        $this->addSubscriptions($subscriptions);
    }

    /**
     * @param array|SubscriptionInterface[] $subscriptions
     */
    public function addSubscriptions(array $subscriptions)
    {
        foreach ($subscriptions as $subscription) {
            $this->addSubscription($subscription);
        }
    }

    /**
     * @param SubscriptionInterface $subscription
     */
    public function addSubscription(SubscriptionInterface $subscription)
    {
        $event = $subscription->getEventClass();
        $listener = $subscription->getListenerClass();

        $this->subscriptions[$event][$listener] = $subscription;
    }

    /**
     * @return array
     */
    public function getSubscriptions(): array
    {
        return $this->subscriptions;
    }

    /**
     * @param object $event
     *   An event for which to return the relevant listeners.
     *
     * @return iterable[callable]
     *   An iterable (array, iterator, or generator) of callables.  Each
     *   callable MUST be type-compatible with $event.
     */
    public function getListenersForEvent(object $event): iterable
    {
        if (!$this->hasEvent($event)) {
            return [];
        }

        $eventClass = get_class($event);

        foreach ($this->resolveListeners($eventClass) as $listener) {
            yield $listener->getCallable();
        }
    }

    /**
     * @param object|string $event
     *
     * @return bool
     */
    public function hasEvent($event): bool
    {
        $eventClass = is_object($event) ? get_class($event) : $event;

        return in_array($eventClass, $this->getEvents());
    }

    /**
     * @return array|string[]
     */
    public function getEvents(): array
    {
        return array_keys($this->subscriptions);
    }

    /**
     * @param string $eventClass
     *
     * @return ListenerInterface[]|\Generator
     */
    protected function resolveListeners(string $eventClass): \Generator
    {
        /** @var SubscriptionInterface[] $sortedSubscriptions */
        $sortedSubscriptions = $this->sortingAlgorithm->sort($this->subscriptions[$eventClass]);

        foreach ($sortedSubscriptions as $subscription) {
            yield $this->resolveListener($subscription->getListenerClass());
        }
    }

    /**
     * @param string $listenerClass
     *
     * @return ListenerInterface
     */
    protected function resolveListener(string $listenerClass): ListenerInterface
    {
        return $this->resolver->resolve($listenerClass);
    }
}
