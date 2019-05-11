<?php namespace NathanBurkett\EventDispatcher\Tests\Integration\DI;

use PHPUnit\Framework\TestCase;

class Primary
{
    /**
     * @var PrimaryDependencyOne
     */
    private $dependencyOne;

    /**
     * @var PrimaryDependencyTwo
     */
    private $dependencyTwo;

    /**
     * @param PrimaryDependencyOne $dependencyOne
     * @param PrimaryDependencyTwo $dependencyTwo
     * @param TestCase $testCase
     */
    public function __construct(PrimaryDependencyOne $dependencyOne, PrimaryDependencyTwo $dependencyTwo, TestCase $testCase)
    {
        $this->dependencyOne = $dependencyOne;
        $this->dependencyTwo = $dependencyTwo;
        $testCase->assertTrue(true);
    }

    /**
     * @return PrimaryDependencyOne
     */
    public function getDependencyOne(): PrimaryDependencyOne
    {
        return $this->dependencyOne;
    }

    /**
     * @return PrimaryDependencyTwo
     */
    public function getDependencyTwo(): PrimaryDependencyTwo
    {
        return $this->dependencyTwo;
    }
}
