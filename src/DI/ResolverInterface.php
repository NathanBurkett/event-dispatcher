<?php namespace NathanBurkett\EventDispatcher\DI;

interface ResolverInterface
{
    /**
     * Build an instance of the given class
     *
     * @param string $class
     *
     * @return object
     */
    public function resolve(string $class);
}
