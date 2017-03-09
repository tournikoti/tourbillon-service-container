<?php

require '../vendor/autoload.php';

require './Service/RandomIntegerService.php';
require './Service/RandomStringService.php';
require './Service/RandomIntegerServiceFactory.php';

use Tourbillon\ServiceContainer\ServiceLocator;

$parameters = array(
    'var1' => 'test 1',
    'var2' => 'test 2',
);

$mainServices = array(
    'app.service.factory' => [
        'class' => 'RandomIntegerServiceFactory',
        'arguments' => ['@app.random.string', 10]
    ],
    'app.random.integer' => [
        'class' => 'RandomIntegerService',
        'arguments' => ['@app.random.string', 10],
        'factory' => ['@app.service.factory', 'createInstance'],
    ],
    'app.random.string' => [
        'class' => 'RandomStringService',
        'arguments' => ['10']
    ]
);

$serviceLocator = new ServiceLocator($parameters, $mainServices);

$service1 = $serviceLocator->get('app.random.integer');
$service2 = $serviceLocator->get('app.random.integer');

var_dump($service); exit;