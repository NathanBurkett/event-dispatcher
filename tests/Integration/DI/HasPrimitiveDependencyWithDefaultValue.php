<?php namespace NathanBurkett\EventDispatcher\Tests\Integration\DI;

class HasPrimitiveDependencyWithDefaultValue
{
    /**
     * @var int
     */
    private $count;

    /**
     * @var PrimaryDependencyOne
     */
    private $dependencyOne;

    public function __construct(PrimaryDependencyOne $dependencyOne, int $count = 0)
    {
        $this->dependencyOne = $dependencyOne;
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
     * @return PrimaryDependencyOne
     */
    public function getDependencyOne(): PrimaryDependencyOne
    {
        return $this->dependencyOne;
    }
}
