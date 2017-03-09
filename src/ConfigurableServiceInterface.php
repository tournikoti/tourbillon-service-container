<?php

namespace Tourbillon\ServiceContainer;

use Tourbillon\Configurator\Configurator;

/**
 * Description of ConfigurableServiceInterface
 *
 * @author gjean
 */
interface ConfigurableServiceInterface
{

    public function configuration(Configurator $configurator);
}
