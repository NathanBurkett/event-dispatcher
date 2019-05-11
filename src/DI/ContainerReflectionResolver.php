<?php namespace NathanBurkett\EventDispatcher\DI;

use Psr\Container\ContainerInterface;
use ReflectionFunctionAbstract;

class ContainerReflectionResolver implements ResolverInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * Build an instance of the given class
     *
     * @param string $class
     *
     * @return object
     * @throws \ReflectionException
     */
    public function resolve(string $class)
    {
        $reflector = new \ReflectionClass($class);

        $this->validateResolvability($reflector);

        $construct = $reflector->getConstructor();

        if (is_null($construct)) {
            return new $class;
        }

        return $reflector->newInstanceArgs($this->reflectArguments($construct));
    }

    /**
     * @param \ReflectionClass $reflector
     *
     * @throws \Exception
     */
    protected function validateResolvability(\ReflectionClass $reflector)
    {
        if (!$reflector->isInstantiable()) {
            $message = sprintf('Unable to resolve %s: not instantiable', $reflector->getName());
            throw new \InvalidArgumentException($message);
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function reflectArguments(ReflectionFunctionAbstract $method): array
    {
        $arguments = [];

        foreach ($method->getParameters() as $parameter) {
            $class = $parameter->getClass();

            if (!is_null($class) && $this->container->has($class->getName())) {
                $arguments[] = $this->container->get($class->getName());
                continue;
            }

            if ($parameter->isDefaultValueAvailable()) {
                $arguments[] = $parameter->getDefaultValue();
                continue;
            }

            $message = sprintf(
                'Unable to resolve a value for parameter (%s) in function/method (%s)',
                $parameter->getName(),
                $method->getName()
            );

            throw new \InvalidArgumentException($message);
        }

        return $arguments;
    }
}
