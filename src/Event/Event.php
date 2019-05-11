<?php namespace NathanBurkett\EventDispatcher\Event;

class Event implements EventInterface
{
    /**
     * @var bool
     */
    protected $stopPropagation = false;

    /**
     * Stop event propagation.
     */
    public function stopPropagation(): void
    {
        $this->stopPropagation = true;
    }

    /**
     * Is propagation stopped?
     * This will typically only be used by the Dispatcher to determine if the
     * previous listener halted propagation.
     *
     * @return bool
     *   True if the Event is complete and no further listeners should be called.
     *   False to continue calling listeners.
     */
    public function isPropagationStopped(): bool
    {
        return $this->stopPropagation;
    }
}
