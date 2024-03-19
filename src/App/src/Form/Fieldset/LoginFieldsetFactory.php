<?php

declare(strict_types=1);

namespace App\Form\Fieldset;

use App\Form\Fieldset\LoginFieldset;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Psr\Container\ContainerInterface;

class LoginFieldsetFactory implements FactoryInterface
{
    /** @inheritDoc */
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null): LoginFieldset
    {
        if ($options !== null) {
            return new $requestedName(options: $options);
        }
        // When using the FactoryInterface $requestedName holds the service identifier registered with the ServiceManager
        return new $requestedName();
    }
}
