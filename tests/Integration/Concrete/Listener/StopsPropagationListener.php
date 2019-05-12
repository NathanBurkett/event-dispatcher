<?php namespace NathanBurkett\EventDispatcher\Tests\Integration\Concrete\Listener;

use NathanBurkett\EventDispatcher\Event\EventInterface;
use NathanBurkett\EventDispatcher\Listener\EventListener;
use NathanBurkett\EventDispatcher\Tests\Integration\Concrete\Event\TrackingEvent;
use PHPUnit\Framework\TestCase;

class StopsPropagationListener extends EventListener
{
    /**
     * @var TestCase
     */
    protected $testCase;

    /**
     * @param TestCase $testCase
     */
    public function __construct(TestCase $testCase)
    {
        $this->testCase = $testCase;
    }

    /**
     * @param EventInterface $event
     */
    public function handle(EventInterface $event)
    {
        $event->stopPropagation();
        $this->testCase->assertTrue(true);

        if ($event instanceof TrackingEvent) {
            $event->addListenersSeen($this);
        }
    }
}
