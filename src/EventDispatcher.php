<?php namespace NathanBurkett\EventDispatcher;

use NathanBurkett\EventDispatcher\Event\EventInterface;
use NathanBurkett\EventDispatcher\Listener\Provider\ListenerProviderInterface;
use Psr\EventDispatcher\StoppableEventInterface;

class EventDispatcher implements EventDispatcherInterface
{
    /**
     * @var array|ListenerProviderInterface[]
     */
    protected $listenerProviders = [];

    /**
     * @param ListenerProviderInterface $listenerProvider
     */
    public function addListenerProvider(ListenerProviderInterface $listenerProvider)
    {
        $name = get_class($listenerProvider);
        $this->listenerProviders[$name] = $listenerProvider;
    }

    /**
     * @return array|ListenerProviderInterface[]
     */
    public function getListenerProviders(): array
    {
        return $this->listenerProviders;
    }

    /**
     * Provide all relevant listeners with an event to process.
     *
     * @param object|EventInterface $event
     *   The object to process.
     *
     * @return object
     *   The Event that was passed, now modified by listeners.
     */
    public function dispatch(object $event): object
    {
        foreach ($this->getListenersFromProvidersForEvent($event) as $listener) {
            // A Dispatcher implementation MUST check to determine if an Event
            // is marked as stopped after each listener is called.  If it is then it should
            // return immediately without calling any further Listeners.
            // per https://github.com/php-fig/event-dispatcher/blob/dbefd12671e8a14ec7f180cab83036ed26714bb0/src/StoppableEventInterface.php#L6-L12
            if ($event instanceof StoppableEventInterface && $event->isPropagationStopped()) {
                break;
            }

            $listener($event);
        }

        return $event;
    }

    /**
     * @param object|EventInterface $event
     *
     * @return \Generator|callable[]
     */
    protected function getListenersFromProvidersForEvent(object $event): \Generator
    {
        foreach ($this->getListenerProviders() as $listenerProvider) {
            yield from $this->getListenersFromProviderForEvent($event, $listenerProvider);
        }
    }

    /**
     * @param object $event
     * @param ListenerProviderInterface $listenerProvider
     *
     * @return \Generator
     */
    protected function getListenersFromProviderForEvent(
        object $event,
        ListenerProviderInterface $listenerProvider
    ): \Generator {
        foreach ($listenerProvider->getListenersForEvent($event) as $callable) {
            yield $callable;
        }
    }
}
