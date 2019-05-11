<?php namespace NathanBurkett\EventDispatcher\Tests\Unit\Sort\Comparator;

use NathanBurkett\EventDispatcher\Sort\Comparator\DescendingSubscriptionComparator;
use NathanBurkett\EventDispatcher\Subscription\Subscription;
use PHPUnit\Framework\TestCase;

class DescendingSubscriptionComparatorTest extends TestCase
{
    public function testSort()
    {
        $fourth = $this->getSubscription(-20);
        $third = $this->getSubscription(0);
        $second = $this->getSubscription(10);
        $first = $this->getSubscription(11);

        $members = [$fourth, $third, $second, $first];

        usort($members, new DescendingSubscriptionComparator());

        /**
         * @var int $index
         * @var Subscription $member
         */
        foreach ($members as $index => $member) {
            switch ($index) {
                case 0:
                    $this->assertEquals($member, $first);
                    break;
                case 1:
                    $this->assertEquals($member, $second);
                    break;
                case 2:
                    $this->assertEquals($member, $third);
                    break;
                case 3:
                    $this->assertEquals($member, $fourth);
                    break;
            }
        }
    }

    public function getSubscription(int $priority)
    {
        $subscription = $this->getMockBuilder(Subscription::class)
                             ->disableOriginalConstructor()
                             ->getMock();

        $subscription->method('getPriority')->willReturn($priority);

        return $subscription;
    }
}
