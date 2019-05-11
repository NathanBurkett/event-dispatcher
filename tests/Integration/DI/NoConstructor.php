<?php namespace NathanBurkett\EventDispatcher\Tests\Integration\DI;

class NoConstructor
{
    /**
     * @return bool
     */
    public function returnTrue(): bool
    {
        return true;
    }
}
