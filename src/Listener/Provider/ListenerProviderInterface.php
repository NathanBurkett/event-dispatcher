<?php namespace NathanBurkett\EventDispatcher\Listener\Provider;

use NathanBurkett\EventDispatcher\Subscription\SubscriptionInterface;

interface ListenerProviderInterface extends \Psr\EventDispatcher\ListenerProviderInterface
{
    /**
     * @return array|string[]
     */
    public function getEvents(): array;

    /**
     * @param object|string $event
     *
     * @return bool
     */
    public function hasEvent($event): bool;

    /**
     * @param array|SubscriptionInterface[] $subscriptions
     */
    public function addSubscriptions(array $subscriptions);

    /**
     * @return array
     */
    public function getSubscriptions(): array;

    /**
     * @param SubscriptionInterface $subscription
     */
    public function addSubscription(SubscriptionInterface $subscription);
}
