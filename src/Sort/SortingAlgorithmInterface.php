<?php namespace NathanBurkett\EventDispatcher\Sort;

interface SortingAlgorithmInterface
{
    /**
     * @param array $items
     *
     * @return array
     */
    public function sort(array $items): array;
}
