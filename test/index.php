<?php

require '../vendor/autoload.php';

require './Service/RandomIntegerService.php';
require './Service/RandomStringService.php';

use Tourbillon\ServiceContainer\ServiceLocator;

$parameters = array(
    'var1' => 'test 1',
    'var2' => 'test 2',
);

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
        'arguments' => ['@app.random.string', '%var1%', 'boom']
    ),
    'app.random.string' => array(
        'class' => 'RandomStringService',
        'arguments' => ['10']
    )
);

$serviceLocator = new ServiceLocator($parameters, $mainServices);

$serviceLocator->add($secondServices);

var_dump($serviceLocator->get('app.random.integer'));
var_dump($serviceLocator->get('app.random.integer')->getRandomStringService());
