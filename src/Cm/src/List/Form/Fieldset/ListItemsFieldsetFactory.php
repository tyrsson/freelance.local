<?php

declare(strict_types=1);

namespace Cm\List\Form\Fieldset;

use Laminas\Db\Adapter\AdapterInterface;
use Psr\Container\ContainerInterface;

final class ListItemsFieldsetFactory
{
    public function __invoke(ContainerInterface $container): ListItemsFieldset
    {
        return ((new ListItemsFieldset())->setDbAdapter($container->get(AdapterInterface::class)));
    }
}
