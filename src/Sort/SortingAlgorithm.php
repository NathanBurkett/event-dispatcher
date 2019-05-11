<?php namespace NathanBurkett\EventDispatcher\Sort;

use NathanBurkett\EventDispatcher\Sort\Comparator\SortingComparator;

class SortingAlgorithm implements SortingAlgorithmInterface
{
    /**
     * @var SortingComparator
     */
    protected $comparator;

    /**
     * @param SortingComparator $comparator
     */
    public function __construct(SortingComparator $comparator)
    {
        $this->comparator = $comparator;
    }

    /**
     * @param array $items
     *
     * @return array
     */
    public function sort(array $items): array
    {
        usort($items, $this->comparator->getCallable());

        return $items;
    }
}
