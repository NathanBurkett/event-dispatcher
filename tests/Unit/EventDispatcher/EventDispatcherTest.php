<?php namespace NathanBurkett\EventDispatcher\Tests\Unit\EventDispatcher;

use NathanBurkett\EventDispatcher\Event\Event;
use NathanBurkett\EventDispatcher\EventDispatcher;
use NathanBurkett\EventDispatcher\Listener\Provider\ListenerProvider;
use NathanBurkett\EventDispatcher\Listener\Provider\ListenerProviderInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class EventDispatcherTest extends TestCase
{
    public function testListenerProviders()
    {
        /** @var MockObject|ListenerProviderInterface $listenerProvider */
        $listenerProvider = $this->getMockBuilder(ListenerProvider::class)
                                  ->disableOriginalConstructor()
                                  ->getMock();

        $dispatcher = new EventDispatcher();

        $dispatcher->addListenerProvider($listenerProvider);

        $this->assertEquals([
            get_class($listenerProvider) => $listenerProvider,
        ], $dispatcher->getListenerProviders());
    }

    public function testDispatch()
    {
        /** @var MockObject|Event $event */
        $event = $this->getMockBuilder(Event::class)
                      ->disableOriginalConstructor()
                      ->getMock();

        $event->expects($this->exactly(2))->method('isPropagationStopped')
              ->willReturn(false, false);

        /** @var MockObject|ListenerProviderInterface $listenerProviderA */
        $listenerProviderA = $this->getMockBuilder(ListenerProvider::class)
                                  ->disableOriginalConstructor()
                                  ->setMockClassName('ListenerProviderA')
                                  ->getMock();

        $listenerProviderA->expects($this->once())
                          ->method('getListenersForEvent')
                          ->with($event)
                          ->willReturn([
                              function ($event) {
                                  return $event;
                              },
                          ]);

        /** @var MockObject|ListenerProviderInterface $listenerProviderB */
        $listenerProviderB = $this->getMockBuilder(ListenerProvider::class)
                                  ->disableOriginalConstructor()
                                  ->setMockClassName('ListenerProviderB')
                                  ->getMock();

        $listenerProviderB->expects($this->once())->method('getListenersForEvent')->with($event)
                          ->willReturn([
                              function ($event) {
                                  return $event;
                              },
                          ]);

        $dispatcher = new EventDispatcher();

        $dispatcher->addListenerProvider($listenerProviderA);
        $dispatcher->addListenerProvider($listenerProviderB);

        $this->assertEquals($event, $dispatcher->dispatch($event));
    }

    public function testDispatchWithEventStoppingPropagation()
    {
        /** @var MockObject|Event $event */
        $event = $this->getMockBuilder(Event::class)
                      ->disableOriginalConstructor()
                      ->getMock();

        $event->expects($this->once())->method('isPropagationStopped')->willReturn(true);

        /** @var MockObject|ListenerProviderInterface $listenerProviderA */
        $listenerProviderA = $this->getMockBuilder(ListenerProvider::class)
                                  ->disableOriginalConstructor()
                                  ->setMockClassName('ListenerProviderA')
                                  ->getMock();

        $listenerProviderA->expects($this->once())
                          ->method('getListenersForEvent')
                          ->with($event)
                          ->willReturn([
                              function ($event) {
                                  return $event;
                              },
                              function ($event) {
                                  throw new \Exception('This will fail the test if reached');
                              },
                          ]);

        /** @var MockObject|ListenerProviderInterface $listenerProviderB */
        $listenerProviderB = $this->getMockBuilder(ListenerProvider::class)
                                  ->disableOriginalConstructor()
                                  ->setMockClassName('ListenerProviderB')
                                  ->getMock();

        $listenerProviderB->expects($this->exactly(0))->method('getListenersForEvent');

        $dispatcher = new \NathanBurkett\EventDispatcher\EventDispatcher();

        $dispatcher->addListenerProvider($listenerProviderA);
        $dispatcher->addListenerProvider($listenerProviderB);

        $this->assertEquals($event, $dispatcher->dispatch($event));
    }
}
