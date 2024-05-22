<?php

declare(strict_types=1);

namespace Cm\List\Form\Fieldset;

use Laminas\Db\Adapter\AdapterInterface;
use Psr\Container\ContainerInterface;

final class ListFieldsetFactory
{
    public function __invoke(ContainerInterface $container): ListFieldset
    {
        return ((new ListFieldset())->setDbAdapter($container->get(AdapterInterface::class)));
    }
}
