<?php namespace NathanBurkett\EventDispatcher\Event;

use Psr\EventDispatcher\StoppableEventInterface;

interface EventInterface extends StoppableEventInterface
{
    /**
     * Stop event propagation.
     */
    public function stopPropagation(): void;
}
