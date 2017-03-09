<?php

/**
 * Description of RandomIntegerServiceFactory
 *
 * @author gjean
 */
class RandomIntegerServiceFactory
{
    private $service;

    private $value;

    private $i = 0;

    public function __construct($service, $value)
    {
        $this->service = $service;
        $this->value = $value;
    }

    public function createInstance()
    {
        return new RandomIntegerService($this->service, $this->value);
    }
}