<?php namespace NathanBurkett\EventDispatcher;

use NathanBurkett\EventDispatcher\Listener\Provider\ListenerProviderInterface;

interface EventDispatcherInterface extends \Psr\EventDispatcher\EventDispatcherInterface
{
    /**
     * @param ListenerProviderInterface $listenerProvider
     */
    public function addListenerProvider(ListenerProviderInterface $listenerProvider);

    /**
     * @return array|ListenerProviderInterface[]
     */
    public function getListenerProviders();
}
