<?php namespace NathanBurkett\EventDispatcher\Sort\Comparator;

use NathanBurkett\EventDispatcher\Subscription\SubscriptionInterface;

class DescendingSubscriptionComparator extends InvokableSortingComparator
{
    /**
     * @param SubscriptionInterface $item1
     * @param SubscriptionInterface $item2
     *
     * @return int
     */
    public function __invoke($item1, $item2): int
    {
        return $item2->getPriority() <=> $item1->getPriority();
    }
}
