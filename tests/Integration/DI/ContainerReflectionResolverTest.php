<?php namespace NathanBurkett\EventDispatcher\Tests\Integration\DI;

use NathanBurkett\EventDispatcher\DI\ContainerReflectionResolver;
use NathanBurkett\EventDispatcher\Tests\Fixtures\UsesContainer;
use PHPUnit\Framework\TestCase;

class ContainerReflectionResolverTest extends TestCase
{
    use UsesContainer;

    public function testResolve()
    {
        $primaryDependencyOne = new PrimaryDependencyOne();
        $this->addToContainer(PrimaryDependencyOne::class, $primaryDependencyOne);
        $primaryDependencyTwo = new PrimaryDependencyTwo();
        $this->addToContainer(PrimaryDependencyTwo::class, $primaryDependencyTwo);
        $this->addToContainer(TestCase::class, $this);

        $resolver = new ContainerReflectionResolver($this->getContainer());

        $instance = $resolver->resolve(Primary::class);
        $this->assertInstanceOf(Primary::class, $instance);
        $this->assertEquals($primaryDependencyOne, $instance->getDependencyOne());
        $this->assertEquals($primaryDependencyTwo, $instance->getDependencyTwo());
    }

    public function testResolveWithNoConstructor()
    {
        $resolver = new ContainerReflectionResolver($this->getContainer());

        $instance = $resolver->resolve(NoConstructor::class);
        $this->assertInstanceOf(NoConstructor::class, $instance);
        $this->assertTrue($instance->returnTrue());
    }

    public function testResolveWithHasPrimitiveDependencyWithDefaultValue()
    {
        $primaryDependencyOne = new PrimaryDependencyOne();
        $this->addToContainer(PrimaryDependencyOne::class, $primaryDependencyOne);

        $resolver = new ContainerReflectionResolver($this->getContainer());

        $instance = $resolver->resolve(HasPrimitiveDependencyWithDefaultValue::class);
        $this->assertInstanceOf(HasPrimitiveDependencyWithDefaultValue::class, $instance);
        $this->assertEquals($primaryDependencyOne, $instance->getDependencyOne());
        $this->assertEquals(0, $instance->getCount());
    }

    public function testResolveWithHasPrimitiveDependencyWithoutDefaultValue()
    {
        $this->expectException(\InvalidArgumentException::class);
        $primaryDependencyTwo = new PrimaryDependencyTwo();
        $this->addToContainer(PrimaryDependencyTwo::class, $primaryDependencyTwo);

        $resolver = new ContainerReflectionResolver($this->getContainer());

        $resolver->resolve(HasPrimitiveDependencyWithoutDefaultValue::class);
    }

    public function testNonInstantiableTrait()
    {
        $this->expectException(\InvalidArgumentException::class);

        $resolver = new ContainerReflectionResolver($this->getContainer());
        $resolver->resolve(NonInstantiableTrait::class);
    }
}
