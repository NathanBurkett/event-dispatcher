<?php namespace NathanBurkett\EventDispatcher\Tests\Integration\Listener;

use NathanBurkett\EventDispatcher\Event\EventInterface;
use NathanBurkett\EventDispatcher\Listener\RetryableEventListener;
use PHPUnit\Framework\TestCase;

class RetryableEventListenerDouble extends RetryableEventListener
{
    /**
     * @var TestCase
     */
    protected $testCase;

    /**
     * @var array
     */
    private $iterationsToThrowException;

    /**
     * @var int
     */
    private $iteration = 0;

    public function __construct(TestCase $testCase, array $iterationsToThrowException = [])
    {
        $this->iterationsToThrowException = $iterationsToThrowException;
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
        $current = $this->iteration;

        $this->iteration++;

        if (in_array($current, $this->iterationsToThrowException)) {
            throw new \Exception();
        }
    }

    protected function tearDown(EventInterface $event)
    {
        parent::tearDown($event);
        $this->testCase->assertTrue(true);
    }
}
