<?php namespace NathanBurkett\EventDispatcher\EventEmitter;

use NathanBurkett\EventDispatcher\Event\EventInterface;
use NathanBurkett\EventDispatcher\EventDispatcherInterface;

class EventEmitter implements EventEmitterInterface
{
    /**
     * @var \NathanBurkett\EventDispatcher\EventDispatcherInterface
     */
    private $dispatcher;

    /**
     * @param EventDispatcherInterface $dispatcher
     */
    public function __construct(EventDispatcherInterface $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    /**
     * @param EventInterface $event
     *
     * @return EventInterface
     */
    public function emit(EventInterface $event): EventInterface
    {
        return $this->dispatcher->dispatch($event);
    }
}
