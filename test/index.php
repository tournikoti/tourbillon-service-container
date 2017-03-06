<?php

require '../vendor/autoload.php';

require './Service/RandomIntegerService.php';
require './Service/RandomStringService.php';

use Tourbillon\ServiceContainer\ServiceLocator;

$services = array(
    'app.random.integer' => array(
        'class' => 'RandomIntegerService',
        'arguments' => [
            'app.random.string'
        ]
    ),
    'app.random.string' => array(
        'class' => 'RandomStringService',
        'arguments' => [
            '10'
        ]
    )
);

$serviceLocator = new ServiceLocator($services);


var_dump($serviceLocator->get('app.random.integer')->getRandomStringService());
