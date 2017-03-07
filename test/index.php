<?php

require '../vendor/autoload.php';

require './Service/RandomIntegerService.php';
require './Service/RandomStringService.php';

use Tourbillon\ServiceContainer\ServiceLocator;

$mainServices = array(
    'request' => array(
        'class' => 'RandomIntegerService',
        'arguments' => ['@app.random.string']
    ),
    'app.random.string' => array(
        'class' => 'RandomStringService',
        'arguments' => ['10']
    )
);

$secondServices = array(
    'app.random.integer' => array(
        'class' => 'RandomIntegerService',
        'arguments' => ['@app.random.string', 'boom']
    ),
    'app.random.string' => array(
        'class' => 'RandomStringService',
        'arguments' => ['10']
    )
);

$serviceLocator = new ServiceLocator($mainServices);

$serviceLocator->add($secondServices);

var_dump($serviceLocator->get('app.random.integer'));
var_dump($serviceLocator->get('app.random.integer')->getRandomStringService());
