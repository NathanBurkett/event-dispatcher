<?php namespace NathanBurkett\EventDispatcher\Tests\Integration\DI;

class HasPrimitiveDependencyWithoutDefaultValue
{
    /**
     * @var int
     */
    private $count;

    /**
     * @var PrimaryDependencyTwo
     */
    private $dependencyTwo;

    public function __construct(PrimaryDependencyTwo $dependencyTwo, int $count)
    {
        $this->dependencyTwo = $dependencyTwo;
        $this->count = $count;
    }

    /**
     * @return int
     */
    public function getCount(): int
    {
        return $this->count;
    }

    /**
     * @return PrimaryDependencyTwo
     */
    public function getDependencyTwo(): PrimaryDependencyTwo
    {
        return $this->dependencyTwo;
    }
}
