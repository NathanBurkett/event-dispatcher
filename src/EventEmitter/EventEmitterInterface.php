<?php namespace NathanBurkett\EventDispatcher\EventEmitter;

use NathanBurkett\EventDispatcher\Event\EventInterface;

interface EventEmitterInterface
{
    /**
     * @param EventInterface $event
     *
     * @return EventInterface
     */
    public function emit(EventInterface $event): EventInterface;
}
