<?php namespace NathanBurkett\EventDispatcher\Tests\Integration\DI;

use NathanBurkett\EventDispatcher\DI\ContainerReflectionResolver;
use NathanBurkett\EventDispatcher\Tests\Fixtures\UsesContainer;
use PHPUnit\Framework\TestCase;

class ContainerReflectionResolverTest extends TestCase
{
    use UsesContainer;

    protected function setUp(): void
    {
        $this->initContainer();
    }

    public function testResolve()
    {
        $primaryDependencyOne = new PrimaryDependencyOne();
        $this->container->add(PrimaryDependencyOne::class, $primaryDependencyOne);
        $primaryDependencyTwo = new PrimaryDependencyTwo();
        $this->container->add(PrimaryDependencyTwo::class, $primaryDependencyTwo);
        $this->container->add(TestCase::class, $this);

        $resolver = new ContainerReflectionResolver($this->container);

        $instance = $resolver->resolve(Primary::class);
        $this->assertInstanceOf(Primary::class, $instance);
        $this->assertEquals($primaryDependencyOne, $instance->getDependencyOne());
        $this->assertEquals($primaryDependencyTwo, $instance->getDependencyTwo());
    }

    public function testResolveWithNoConstructor()
    {
        $resolver = new ContainerReflectionResolver($this->container);

        $instance = $resolver->resolve(NoConstructor::class);
        $this->assertInstanceOf(NoConstructor::class, $instance);
        $this->assertTrue($instance->returnTrue());
    }

    public function testResolveWithHasPrimitiveDependencyWithDefaultValue()
    {
        $primaryDependencyOne = new PrimaryDependencyOne();
        $this->container->add(PrimaryDependencyOne::class, $primaryDependencyOne);

        $resolver = new ContainerReflectionResolver($this->container);

        $instance = $resolver->resolve(HasPrimitiveDependencyWithDefaultValue::class);
        $this->assertInstanceOf(HasPrimitiveDependencyWithDefaultValue::class, $instance);
        $this->assertEquals($primaryDependencyOne, $instance->getDependencyOne());
        $this->assertEquals(0, $instance->getCount());
    }

    public function testResolveWithHasPrimitiveDependencyWithoutDefaultValue()
    {
        $this->expectException(\InvalidArgumentException::class);
        $primaryDependencyTwo = new PrimaryDependencyTwo();
        $this->container->add(PrimaryDependencyTwo::class, $primaryDependencyTwo);

        $resolver = new ContainerReflectionResolver($this->container);

        $resolver->resolve(HasPrimitiveDependencyWithoutDefaultValue::class);
    }

    public function testNonInstantiableTrait()
    {
        $this->expectException(\InvalidArgumentException::class);

        $resolver = new ContainerReflectionResolver($this->container);
        $resolver->resolve(NonInstantiableTrait::class);
    }
}
