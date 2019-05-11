<?php namespace NathanBurkett\EventDispatcher\Tests\Integration\Listener;

use NathanBurkett\EventDispatcher\Event\Event;
use NathanBurkett\EventDispatcher\Event\EventInterface;
use NathanBurkett\Mesa\Fixture\PHPUnit\GeneratesTestCases;
use PHPUnit\Framework\TestCase;

class RetryableEventListenerTest extends TestCase
{
    use GeneratesTestCases;

    /**
     * @dataProvider generateHandleTestCases
     *
     * @param array $exceptionIterations
     * @param EventInterface $event
     */
    public function testHandle(array $exceptionIterations, EventInterface $event)
    {
        $listener = new RetryableEventListenerDouble($this, $exceptionIterations);
        $callable = $listener->getCallable();
        $callable($event);
    }

    /**
     * Generates testHandle test cases.
     */
    public function generateHandleTestCases(): \Generator
    {
        yield from $this->generateTestCases(
            'HandleTestCases.php',
            [$this, 'setupHandleTestCase']
        );
    }

    public function setupHandleTestCase(array $testCase): array
    {
        $eventMock = $this->getMockBuilder(Event::class)
                          ->disableOriginalConstructor()
                          ->getMock();

        return [
            $testCase['exceptionIterations'],
            $testCase['handleEvent']($eventMock, $this),
        ];
    }
}
