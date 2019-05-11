<?php namespace NathanBurkett\EventDispatcher\Tests\Integration\Listener;

use NathanBurkett\EventDispatcher\Event\EventInterface;
use NathanBurkett\EventDispatcher\Listener\EventListener;
use PHPUnit\Framework\TestCase;

class EventListenerDouble extends EventListener
{
    /**
     * @var TestCase
     */
    protected $testCase;

    public function __construct(TestCase $testCase)
    {
        $this->testCase = $testCase;
    }

    /**
     * @param EventInterface $event
     */
    public function handle(EventInterface $event)
    {
        $this->testCase->assertTrue(true);
    }
}
