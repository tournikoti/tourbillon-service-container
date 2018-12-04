<?php

namespace Tourbillon\ServiceContainer;

use Exception;
use ReflectionClass;
use Tourbillon\Configurator\Configurator;

/**
 * Description of ServiceContainer
 *
 * @author gjean
 */
class ServiceLocator
{
    /**
     *
     * @var Configurator
     */
    private $configurator;
    private $services;

    public function __construct(Configurator $configurator, array $services)
    {
        $this->configurator = $configurator;
        $this->services   = $services;
    }

    public function get($name)
    {
        if (!isset($this->services[$name])) {
            throw new Exception("service {$name} does not exist");
        }

        if (array_key_exists('factory', $this->services[$name])) {
            return $this->createInstanceByFactory($name);
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
        $reflection = new ReflectionClass($this->services[$name]['class']);
        $instance = $reflection->newInstanceArgs($this->getArguments($name));

        if ($instance instanceof ConfigurableServiceInterface) {
            $instance->configuration($this->configurator);
        }

        return $instance;
    }

    private function createInstanceByFactory($name)
    {
        if (0 === strpos($this->services[$name]['factory'][0], '@')) {
            $serviceName = substr($this->services[$name]['factory'][0], 1);
            $service = $this->get($serviceName);

            $action = $this->services[$name]['factory'][1];

            return call_user_func_array([$service, $action], $this->getArguments($name));
        } else {
            return call_user_func_array($this->services[$name]['factory'], $this->getArguments($name));
        }
    }

    private function getArguments($name)
    {
        $arguments = array();
        if (isset($this->services[$name]['arguments'])) {
            foreach ($this->services[$name]['arguments'] as $argument) {
                $arguments[] = $this->getArgument($argument);
            }
        }

        return $arguments;
    }

    private function getArgument($argument)
    {
        if (!is_array($argument)) {
            if (0 === strpos($argument, '@')) {
                $serviceName = substr($argument, 1);
                if (!isset($this->services[$serviceName])) {
                    throw new Exception("Service {$serviceName} does not exist");
                }
                return $this->get($serviceName);
            } else if (preg_match('/%([^%]*)%/', $argument, $matches)) {
                if (!array_key_exists($matches[1], $this->configurator->getParameters())) {
                    throw new Exception("Parameter {$matches[1]} does not exist");
                }
                return $this->configurator->getParameter($matches[1]);
            }
        }

        return $argument;
    }

}