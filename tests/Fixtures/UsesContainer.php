<?php namespace NathanBurkett\EventDispatcher\Tests\Fixtures;

use League\Container\Container;
use Psr\Container\ContainerInterface;

trait UsesContainer
{
    /**
     * @var ContainerInterface|Container
     */
    protected $container;

    protected function initContainer()
    {
        $this->container = $this->getContainer();
    }

    /**
     * @return ContainerInterface
     */
    protected function getContainer()
    {
        return new Container();
    }
}
