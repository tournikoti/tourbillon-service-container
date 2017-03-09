<?php

namespace Tourbillon\ServiceContainer;

use ReflectionClass;
use Exception;

/**
 * Description of ServiceContainer
 *
 * @author gjean
 */
class ServiceLocator
{
    private $parameters;
    
    private $services;

    public function __construct(array $parameters, array $services)
    {
        $this->parameters = $parameters;
        $this->services = $services;
    }

    public function get($name)
    {
        if (!isset($this->services[$name])) {
            throw new Exception("service {$name} does not exist");
        }

        if (!is_object($this->services[$name])) {
            $this->services[$name] = $this->createInstance($name);
        }

        return $this->services[$name];
    }

    public function addServices(array $services)
    {
        $this->services = array_merge($this->services, $services);
    }

    public function addService($name, $service)
    {
        if (array_key_exists($name, $this->services)) {
            throw new Exception("A service {$name} already exist");
        }

        $this->services[$name] = $service;
    }

    private function createInstance($name)
    {
        $arguments = array();
        if (isset($this->services[$name]['arguments'])) {
            foreach ($this->services[$name]['arguments'] as $argument) {
                if (0 === strpos($argument, '@')) {
                    $serviceName = substr($argument, 1);
                    if (!isset($this->services[$serviceName])) {
                        throw new Exception("Service {$serviceName} does not exist");
                    }
                    $arguments[] = $this->get($serviceName);
                } else if (preg_match('/%([^%]*)%/', $argument, $matches)) {
                    if (!array_key_exists($matches[1], $this->parameters)) {
                        throw new Exception("Parameter {$matches[1]} does not exist");
                    }
                    $arguments[] = $this->parameters[$matches[1]];
                } else {
                    $arguments[] = $argument;
                }
            }
        }

        $reflection = new ReflectionClass($this->services[$name]['class']);
        return $reflection->newInstanceArgs($arguments);
    }
}
