<?php namespace NathanBurkett\EventDispatcher\Listener;

abstract class EventListener implements EventHandlerInterface
{
    /**
     * @return callable
     */
    public function getCallable(): callable
    {
        return [$this, 'handle'];
    }
}
