<?php

/**
 * Description of GetIntegerService
 *
 * @author gjean
 */
class RandomIntegerService
{

    private $service;

    private $value;

    public function __construct(RandomStringService $service, $value)
    {
        $this->service = $service;
        $this->value = $value;
    }

    public function getRandomStringService()
    {
        return $this->service;
    }
}