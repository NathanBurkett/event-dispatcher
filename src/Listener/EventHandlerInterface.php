<?php namespace NathanBurkett\EventDispatcher\Listener;

use NathanBurkett\EventDispatcher\Event\EventInterface;

interface EventHandlerInterface extends ListenerInterface
{
    /**
     * @param EventInterface $event
     */
    public function handle(EventInterface $event);
}
