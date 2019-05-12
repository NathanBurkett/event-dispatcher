<?php namespace NathanBurkett\EventDispatcher\Tests\Integration\Concrete\Listener;

use NathanBurkett\EventDispatcher\Event\EventInterface;
use NathanBurkett\EventDispatcher\Listener\EventListener;
use PHPUnit\Framework\TestCase;

class UnreachableEventListener extends EventListener
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
        $this->testCase->fail(sprintf('Expected %s::handle() to not be called', static::class));
    }
}
