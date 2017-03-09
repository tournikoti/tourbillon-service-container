<?php

require '../vendor/autoload.php';

require './Service/RandomIntegerService.php';
require './Service/RandomStringService.php';
require './Service/RandomIntegerServiceFactory.php';

use Tourbillon\Configurator\ConfiguratorFactory;
use Tourbillon\ServiceContainer\ServiceLocator;

$configurator = ConfiguratorFactory::createInstance('config/config.neon');

var_dump($configurator);

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
        'arguments' => ['10'],
        'config' => 'random'
    ]
);

$serviceLocator = new ServiceLocator($configurator, $mainServices);

$service1 = $serviceLocator->get('app.random.string');
$service2 = $serviceLocator->get('app.random.integer');

var_dump($service1, $service2); exit;