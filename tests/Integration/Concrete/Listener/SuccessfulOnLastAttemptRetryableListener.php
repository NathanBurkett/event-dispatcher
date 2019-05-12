<?php namespace NathanBurkett\EventDispatcher\Tests\Integration\Concrete\Listener;

use NathanBurkett\EventDispatcher\Event\EventInterface;
use NathanBurkett\EventDispatcher\Listener\RetryableEventListener;
use NathanBurkett\EventDispatcher\Tests\Integration\Concrete\Event\TrackingEvent;
use PHPUnit\Framework\TestCase;

class SuccessfulOnLastAttemptRetryableListener extends RetryableEventListener
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
     * Handle the event.
     *
     * @param EventInterface $event
     *
     * @throws \Exception
     */
    protected function doHandle(EventInterface $event)
    {
        if ($this->failureCount !== ($this->retryLimit - 1)) {
            throw new \Exception();
        }

        $this->testCase->assertTrue(true);
        if ($event instanceof TrackingEvent) {
            $event->addListenersSeen($this);
        }
    }
}
