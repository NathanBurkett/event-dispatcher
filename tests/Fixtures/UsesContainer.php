<?php namespace NathanBurkett\EventDispatcher\Tests\Fixtures;

use League\Container\Container;
use Psr\Container\ContainerInterface;

trait UsesContainer
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @return ContainerInterface|Container
     */
    protected function getContainer()
    {
        if (!$this->container) {
            $this->container = $this->getNewContainerInstance();
        }

        return $this->container;
    }

    /**
     * @return ContainerInterface
     */
    protected function getNewContainerInstance()
    {
        return new Container();
    }

    /**
     * @param string $key
     * @param mixed|null $concrete
     *
     * @return \League\Container\Definition\DefinitionInterface
     */
    protected function addToContainer(string $key, $concrete = null)
    {
        return $this->getContainer()->add($key, $concrete);
    }
}
