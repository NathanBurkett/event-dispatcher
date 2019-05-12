<?php namespace NathanBurkett\EventDispatcher\Tests\Integration\Concrete\Event;

use NathanBurkett\EventDispatcher\Event\Event;
use NathanBurkett\EventDispatcher\Listener\EventListener;

class TrackingEvent extends Event
{
    /**
     * @var array|string[]
     */
    protected $listenersSeen = [];

    /**
     * @param EventListener $eventListener
     */
    public function addListenersSeen(EventListener $eventListener)
    {
        $this->listenersSeen[] = get_class($eventListener);
    }

    /**
     * @return array|string[]
     */
    public function getListenersSeen()
    {
        return $this->listenersSeen;
    }
}
