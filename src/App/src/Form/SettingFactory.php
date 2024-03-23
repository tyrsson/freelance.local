<?php

declare(strict_types=1);

namespace App\Form;

use App\Form\Setting;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Psr\Container\ContainerInterface;

class SettingFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null): Setting
    {
        if ($options !== null) {
            return new $requestedName(options: $options);
        }
        return new $requestedName();
    }
}
