<?php namespace NathanBurkett\EventDispatcher\Common;

interface ReturnsCallable
{
    public function getCallable(): callable;
}
