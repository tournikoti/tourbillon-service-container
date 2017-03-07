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
    private $services;

    public function __construct(array $services)
    {
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

    public function add(array $services)
    {
        $this->services = array_merge($this->services, $services);
    }

    private function createInstance($name)
    {
        $arguments = array();
        if (isset($this->services[$name]['arguments'])) {
            foreach ($this->services[$name]['arguments'] as $argument) {
                if (0 === strpos($argument, '@')) {
                    $serviceName = substr($argument, 1);
                    if (!isset($this->services[$serviceName])) {
                        throw new Exception("Parameters {$serviceName} does not exist");
                    }
                    $arguments[] = $this->get($serviceName);
                } else {
                    $arguments[] = $argument;
                }
            }
        }

        $reflection = new ReflectionClass($this->services[$name]['class']);
        return $reflection->newInstanceArgs($arguments);
    }
}
