<?php namespace NathanBurkett\EventDispatcher\Tests\Unit\Sort;

use NathanBurkett\EventDispatcher\Sort\Comparator\InvokableSortingComparator;
use NathanBurkett\EventDispatcher\Sort\Comparator\SortingComparator;
use NathanBurkett\EventDispatcher\Sort\SortingAlgorithm;
use PHPUnit\Framework\TestCase;

class SortingAlgorithmTest extends TestCase
{
    public function testSort()
    {
        $comparator = $this->getMockBuilder(InvokableSortingComparator::class)
                           ->disableOriginalConstructor()
                           ->getMockForAbstractClass();

        $comparator->method('__invoke')->willReturn(1, -1);

        $algorithm = new SortingAlgorithm($comparator);

        $expectLast = 'last';
        $expectMiddle = 'middle';
        $expectFirst = 'first';

        $expected = [$expectFirst, $expectMiddle, $expectLast];
        $this->assertEquals($expected, $algorithm->sort([$expectLast, $expectMiddle, $expectFirst]));
    }
}
