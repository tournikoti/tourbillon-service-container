<?php

/**
 * Description of GetIntegerService
 *
 * @author gjean
 */
class RandomIntegerService
{

    private $randomStringService;


    public function __construct($randomStringService)
    {
        $this->randomStringService = $randomStringService;
    }

    public function getRandomStringService()
    {
        return $this->randomStringService;
    }
}