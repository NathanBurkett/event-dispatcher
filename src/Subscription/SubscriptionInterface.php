<?php namespace NathanBurkett\EventDispatcher\Subscription;

interface SubscriptionInterface
{
    /**
     * @return string
     */
    public function getEventClass(): string;

    /**
     * @return string
     */
    public function getListenerClass(): string;

    /**
     * @return int
     */
    public function getPriority(): int;
}
