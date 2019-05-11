<?php namespace NathanBurkett\EventDispatcher\Listener;

use NathanBurkett\EventDispatcher\Event\EventInterface;

abstract class RetryableEventListener extends EventListener
{
    /**
     * @var int
     */
    protected $retryLimit = 10;

    /**
     * @var int
     */
    protected $failureCount = 0;

    /**
     * @param EventInterface $event
     */
    public function handle(EventInterface $event)
    {
        $this->setUp($event);

        do {
            try {
                $this->doHandle($event);
                break;
            } catch (\Exception $exception) {
                $this->failureCount++;
                $this->recoverFailure($exception, $event);
            }
        } while ($this->failureCount < $this->retryLimit);

        $this->tearDown($event);
    }

    /**
     * Handle the event.
     *
     * @param EventInterface $event
     */
    abstract protected function doHandle(EventInterface $event);

    /**
     * Recover from a failed handling.
     *
     * @param \Exception $exception
     * @param EventInterface $event
     */
    protected function recoverFailure(\Exception $exception, EventInterface $event)
    {
    }

    /**
     * @param EventInterface $event
     */
    protected function setUp(EventInterface $event)
    {
    }

    /**
     * @param EventInterface $event
     */
    protected function tearDown(EventInterface $event)
    {
    }
}
