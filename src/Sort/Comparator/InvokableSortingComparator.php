<?php namespace NathanBurkett\EventDispatcher\Sort\Comparator;

abstract class InvokableSortingComparator implements SortingComparator
{
    /**
     * @param mixed $item1
     * @param mixed $item2
     *
     * @return int
     */
    abstract public function __invoke($item1, $item2): int;

    /**
     * @return callable
     */
    public function getCallable(): callable
    {
        return $this;
    }
}
