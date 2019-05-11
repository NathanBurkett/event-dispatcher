<?php namespace NathanBurkett\EventDispatcher\Subscription;

class Subscription implements SubscriptionInterface
{
    /**
     * @var string
     */
    private $eventClass;

    /**
     * @var string
     */
    private $listenerClass;

    /**
     * @var int
     */
    private $priority;

    /**
     * @param string $eventClass
     * @param string $listenerClass
     * @param int $priority
     */
    public function __construct(string $eventClass, string $listenerClass, int $priority = 0)
    {
        $this->eventClass = $eventClass;
        $this->listenerClass = $listenerClass;
        $this->priority = $priority;
    }

    /**
     * @return string
     */
    public function getEventClass(): string
    {
        return $this->eventClass;
    }

    /**
     * @return string
     */
    public function getListenerClass(): string
    {
        return $this->listenerClass;
    }

    /**
     * @return int
     */
    public function getPriority(): int
    {
        return $this->priority;
    }
}
