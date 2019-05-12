<?php namespace NathanBurkett\EventDispatcher\Tests\Integration\Concrete\Listener;

use NathanBurkett\EventDispatcher\Event\EventInterface;
use NathanBurkett\EventDispatcher\Listener\EventListener;
use NathanBurkett\EventDispatcher\Tests\Integration\Concrete\Event\TrackingEvent;
use PHPUnit\Framework\TestCase;

class RequiredListener extends EventListener
{
    /**
     * @var TestCase
     */
    protected $testCase;

    /**
     * @var bool
     */
    protected $handled = false;

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
        $this->handled = true;
        $this->testCase->assertTrue(true);

        if ($event instanceof TrackingEvent) {
            $event->addListenersSeen($this);
        }
    }

    public function __destruct()
    {
        if (!$this->handled) {
            $this->testCase->fail(sprintf('Expected %s::handle to be called', static::class));
        }
    }
}
